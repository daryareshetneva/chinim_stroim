<?php

class Certificates_Bootstrap extends Zend_Application_Module_Bootstrap {
    protected function _initRouter(){
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();

        $router->addRoute('/certificates/index/index', new Zend_Controller_Router_Route(
            '/certificates/', array(
                'module' => 'certificates',
                'controller' => 'index',
                'action' => 'index'
            )
        ));

        $router->addRoute('/certificates404', new Zend_Controller_Router_Route(
            '/certificates/:alias', array(
                'module' => 'certificates',
                'controller' => 'index',
                'action' => 'index',
                'alias' => 'index'
            )
        ));

        $router->addRoute('certificatesPages', new Zend_Controller_Router_Route(
            '/certificates/page/:page', array(
                'module' => 'certificates',
                'controller' => 'index',
                'action' => 'index',
                'page' => null
            )
        ));

        $router->addRoute('certificatesAdmin', new Zend_Controller_Router_Route(
            '/certificates/admin', array(
                'module' => 'certificates',
                'controller' => 'admin',
                'action' => 'index'
            )
        ));
        
        return $router;
    }
}
