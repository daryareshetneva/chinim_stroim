<?php

class Portfolio_Bootstrap extends Zend_Application_Module_Bootstrap{

    protected function _initRouter(){
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();
        
        $router->addRoute('/portfolio', new Zend_Controller_Router_Route(
            '/portfolio', array(
                'module' => 'portfolio',
                'controller' => 'index',
                'action' => 'index'
            )));

        $router->addRoute('/portfolio/show', new Zend_Controller_Router_Route(
            '/portfolio/project/:alias', array(
                'module' => 'portfolio',
                'controller' => 'index',
                'action' => 'show'
            )));
        
        return $router;
    }
    
}
