<?php
class Services_Bootstrap extends Zend_Application_Module_Bootstrap {
    protected function _initRouter(){
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();


        $router->addRoute('/services', new Zend_Controller_Router_Route(
            '/services/:alias', array(
            'module' => 'services',
            'controller' => 'index',
            'action' => 'index',
            'alias' => null
        )));

        $router->addRoute('/services/showId', new Zend_Controller_Router_Route(
            '/services/show/id/:id', array(
            'module' => 'services',
            'controller' => 'index',
            'action' => 'show'
        )));

        $router->addRoute('/services/showAlias', new Zend_Controller_Router_Route(
            '/services/show/:alias', array(
            'module' => 'services',
            'controller' => 'index',
            'action' => 'show',
            'alias' => null
        )));

        $router->addRoute('/services/admin', new Zend_Controller_Router_Route(
            '/services/admin', array(
                'module' => 'services',
                'controller' => 'admin',
                'action' => 'index'
            )
        ));
        return $router;
    }
}