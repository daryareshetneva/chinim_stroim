<?php

class HotelRooms_Bootstrap extends Zend_Application_Module_Bootstrap {
    protected function _initRouter(){
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();

        $router->addRoute('/apartments', new Zend_Controller_Router_Route(
            '/apartments/:alias', array(
                'module' => 'hotelrooms',
                'controller' => 'index',
                'action' => 'index',
                'alias' => null
            )
        ));

        $router->addRoute('/apartmentsShow', new Zend_Controller_Router_Route(
            '/apartments/show/:alias', array(
                'module' => 'hotelrooms',
                'controller' => 'index',
                'action' => 'show',
                'alias' => null
            )
        ));
        
        return $router;
    }
}
