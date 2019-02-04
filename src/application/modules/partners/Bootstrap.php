<?php

class Partners_Bootstrap extends Zend_Application_Module_Bootstrap {
    protected function _initRouter(){
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();

        $router->addRoute('/partners', new Zend_Controller_Router_Route(
            '/partners', array(
                'module' => 'partners',
                'controller' => 'index',
                'action' => 'index'
            )
        ));
        
        return $router;
    }
}
