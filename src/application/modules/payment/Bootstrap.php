<?php

class Payment_Bootstrap extends Zend_Application_Module_Bootstrap {
    
    protected $_router;

    public function __construct($application) {
        parent::__construct($application);
        
        $frontController = Zend_Controller_Front::getInstance();
        $this->_router = $frontController->getRouter();
    }

    protected function _initRouter(){
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
                            $page['alias'], array(
                                'module' => $page['module'],
                                'controller' => $page['controller'],
                                'action' => $page['action']
                            )));
                    }
                    
                    $routerNames[] = $curRouterName;
                }
            }
        }
        
        return $routerNames;
    }
    
}