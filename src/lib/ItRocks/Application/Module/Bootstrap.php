<?php

class ItRocks_Application_Module_Bootstrap extends Zend_Application_Module_Bootstrap {

    protected $_moduleName;
    protected $_router;
    protected $_routerNames = null;

    public function __construct($application) {
        $frontController = Zend_Controller_Front::getInstance();
        $this->_router = $frontController->getRouter();
        $this->_moduleName = mb_strtolower($this->getModuleName(), 'UTF-8');
        
        parent::__construct($application);
    }
    
    protected function _getRouters() {
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
                $this->_getRouters()->addRoute($curRouterName, $route);
            }
        }
    }

    protected function _initDynamicRouter() {
        $tagsModel = new Model_Tags();
        $tags = $tagsModel->getTagsInBootstrap($this->_moduleName);
        $routerNames = array('notempty');

        if ($tags) {
            foreach ($tags as $page) {
                if ($page['alias'] != '') {
                    $curRouterName = "/{$page['module']}/{$page['controller']}/{$page['action']}";
                    $this->_getRouters()->addRoute(
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

}
