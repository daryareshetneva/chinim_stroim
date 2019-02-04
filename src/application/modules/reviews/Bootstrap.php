<?php

class Reviews_Bootstrap extends Zend_Application_Module_Bootstrap {
    protected function _initRouter(){
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();
        
        $router->addRoute('/testimonials', new Zend_Controller_Router_Route(
                '/reviews/page/:page', array(
                    'module' => 'reviews',
                    'controller' => 'index',
                    'action' => 'index'
        )));

        $router->addRoute('/reviews', new Zend_Controller_Router_Route(
            '/reviews', array(
                'module' => 'reviews',
                'controller' => 'index',
                'action' => 'index'
            )
        ));
        
        return $router;
    }
}
