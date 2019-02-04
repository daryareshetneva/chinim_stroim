<?php

class Static_Bootstrap extends Zend_Application_Module_Bootstrap {
    protected $_router;
    protected $_getParams = array( 'alias' );

    public function __construct($application) {
        parent::__construct($application);
        
        $frontController = Zend_Controller_Front::getInstance();
        $this->_router = $frontController->getRouter();
    }
    
    protected function _initRouter() {
        $module = 'static';
        
        $routerNames = $this->_initDynamicRouter($module);

        $curRouterName = "/{$module}/index/index";
        $this->_router->addRoute($curRouterName, new Zend_Controller_Router_Route(
            '/page/:alias', array(
                'module' => $module,
                'controller' => 'index',
                'action' => 'index',
                'alias' => 'home'
            )
        ));

        $this->_router->addRoute('home', new Zend_Controller_Router_Route(
            '/home', array(
                'module' => $module,
                'controller' => 'index',
                'action' => 'index',
                'alias' => 'home'
            )
        ));

        $this->_router->addRoute('contacts', new Zend_Controller_Router_Route(
            '/page/:alias', array(
                'module' => $module,
                'controller' => 'index',
                'action' => 'index',
                'alias' => 'contacts'
            )
        ));

        $this->_router->addRoute('about', new Zend_Controller_Router_Route(
            '/page/:alias', array(
                'module' => $module,
                'controller' => 'index',
                'action' => 'index',
                'alias' => 'about'
            )
        ));

        return $this->_router;
    }
    
    private function _initDynamicRouter($module) {
        $tagsModel = new Model_Tags();
        $tags = $tagsModel->getTagsInBootstrap($module);
        $routerNames = array('test');
        
        if ($tags) {
            foreach ($tags as $page) {
                $curRouterName = "/{$module}/{$page['controller']}/{$page['action']}";
                if ($page['alias'] != '') {
                    
                    $curRouterName = "/{$module}/{$page['controller']}/{$page['action']}";
                    if ($page['alias'] != '') {
                        $this->_router->addRoute($curRouterName, new Zend_Controller_Router_Route(
                            $page['alias'] . "/*", array(
                                'module' => $page['module'],
                                'controller' => $page['controller'],
                                'action' => $page['action']
                            ), $this->_getParams ));
                    }
                    
                    $routerNames[] = $curRouterName;
                }
            }
        }
        
        return $routerNames;
    }
    
}