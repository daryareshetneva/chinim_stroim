<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected $_moduleName;
    protected $_router;
    protected $_routerNames;
    private static $_moduleList = [];

    public function __construct($application) {
        parent::__construct($application);

        $frontController = Zend_Controller_Front::getInstance();
        $this->_router = $frontController->getRouter();
        $this->_moduleName = 'default';
    }

    public static function getModuleList(){
        return self::$_moduleList;
    }

    public static function addModule($moduleName, $status = 0){
        self::$_moduleList[$moduleName] = trim($status);
    }

    protected function _initFrontModules() {
        $this->bootstrap('frontController');
        $front = $this->getResource('frontController');
        $front->addModuleDirectory(APPLICATION_PATH . '/modules');
    }

    protected function _initAutoload() {
        $modulePath = APPLICATION_PATH . "/modules/";
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ItRocks_');
        $front = Zend_Controller_Front::getInstance();

        //path for helpers
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . '/helpers',
                'Helper_');
        //file for pagination
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/pagination.phtml');

        $main = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH
        ));

        $main->addResourceType('Validator', 'validators', 'Validator_');

        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH
        ));

        $modules = new DirectoryIterator(APPLICATION_PATH . '/modules/');
        $defaultModule['default'] = APPLICATION_PATH . '/controllers';
        $activeModules = array();

        foreach ($modules as $folder) {
            if ($folder->getFilename() != '.' && $folder->getFilename() != '..') {
                if (file_exists($modulePath . $folder . '/configs/module.xhtml')) {
                    $config = new Zend_Config_Xml($modulePath . $folder . '/configs/module.xhtml',
                            'module');
                    if ($config->status) {
                        $activeModules[$folder->getFileName()] = APPLICATION_PATH . '/modules/' . $folder . '/controllers';
                        $module = new Zend_Application_Module_Autoloader(array(
                            'namespace' => ucfirst($folder) . '_',
                            'basePath' => APPLICATION_PATH . '/modules/' . $folder,
                            'form' => array('path' => 'forms', 'namespace' => 'Form'),
                            'model' => array('path' => 'models', 'namespace' => 'Model'),
                            'plugin' => array('path' => 'plugins', 'namespace' => 'Plugin')
                        ));
                    }
                }
            }
        }
        $front->setControllerDirectory(array_merge($defaultModule,
                        $activeModules));

        return $front;
    }

    protected function _initDbChecker() {
        $this->bootstrap('frontController');
        $fc = $this->getResource('frontController');
        $fc->registerPlugin(new Plugin_DbChecker($this));
    }

    protected function _initModuleLoader() {
        $this->bootstrap('frontController');
        $fc = $this->getResource('frontController');
        $fc->registerPlugin(new Plugin_ModuleLoader);
    }

    protected function _initLang() {
        $this->bootstrap('frontController');
        $fc = $this->getResource('frontController');
        $fc->registerPlugin(new Plugin_LangLoader);
        $locale = new Zend_Locale('ru');
        Zend_Locale::setDefault($locale);
    }

    protected function _initAuth() {
        $this->bootstrap('frontController');
        $fc = $this->getResource('frontController');
        $fc->registerPlugin(new Plugin_Auth);
    }

    protected function _initRouter() {
        $this->_initDynamicRouter();

        $this->_initDefaultRouter('error', 'error', '/404');
        $this->_initDefaultRouter('index', 'search', '/search');

        $this->_router->addRoute(
                "/static/index/home", new Zend_Controller_Router_Route(
                    '/',
                    array(
                        'module' => 'static',
                        'controller' => 'index',
                        'action' => 'home'
        )));

        $this->_router->addRoute(
            "/feedback-success", new Zend_Controller_Router_Route(
            '/feedback-success',
            array(
                'module' => 'default',
                'controller' => 'index',
                'action' => 'feedback-success'
            )));

        return $this->_router;
    }
    protected function _initDefaultRouter($controller = null, $action = null, $alias = null) {
        if ( $this->_routerNames && $controller && $action && $alias ) {
            $curRouterName = "/{$this->_moduleName}/{$controller}/{$action}";
            $params = array(
                    'module' => $this->_moduleName,
                    'controller' => $controller,
                    'action' => $action
            );
            $route = new Zend_Controller_Router_Route($alias, $params);

            if (!in_array($curRouterName, $this->_routerNames)) {
                $this->_router->addRoute($curRouterName, $route);
            }
        }
    }

    private function _initDynamicRouter() {
        $tagsModel = new Model_Tags();
        $tags = $tagsModel->getTagsInBootstrap('default');
        $routerNames = array('notempty');

        if ($tags) {
            foreach ($tags as $page) {
                if ($page['alias'] != '') {
                    $curRouterName = "/{$page['module']}/{$page['controller']}/{$page['action']}";
                    $this->_router->addRoute(
                            $curRouterName, new Zend_Controller_Router_Route(
                                $page['alias'],
                                array(
                                    'module' => $page['module'],
                                    'controller' => $page['controller'],
                                    'action' => $page['action']
                    )));

                    $routerNames[] = $curRouterName;
                }
            }
        }
        $this->_routerNames = $routerNames;
    }

    protected function _initFm() {
        $this->bootstrap('frontController');
        $fc = $this->getResource('frontController');
        $fc->registerPlugin(new Plugin_FlashMessenger);
    }

    protected function _initViewHelpers() {
        $this->bootstrap('view');
        $view = $this->getResource('view');

        $view->addHelperPath('ItRocks/View/Helper', 'ItRocks_View_Helper');
        $view->doctype('HTML5');
        $view->headMeta()->appendHttpEquiv(
                'Content-Type', 'text/html;charset=utf-8'
        );
    }

//    protected function _initNv() {
//        $this->bootstrap('frontController');
//        $fc = $this->getResource('frontController');
//        $fc->registerPlugin(new Plugin_Pages($this));
//    }

    protected function _initConfig() {
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $config);

        return $config;
    }

}
