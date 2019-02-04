<?php

class Specialists_Bootstrap extends Zend_Application_Module_Bootstrap {
    protected function _initRouter(){
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();

        $router->addRoute('/specialists', new Zend_Controller_Router_Route(
            '/specialists', array(
                'module' => 'specialists',
                'controller' => 'index',
                'action' => 'index'
            )
        ));
        $router->addRoute('/specialists/show', new Zend_Controller_Router_Route(
            '/specialists/show/:id', array(
                'module' => 'specialists',
                'controller' => 'index',
                'action' => 'show'
            )
        ));
        
        return $router;
    }
}
