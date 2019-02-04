<?php

class Gallery_Bootstrap extends Zend_Application_Module_Bootstrap {

    protected function _initRouter() {
        $router = Zend_Controller_Front::getInstance()->getRouter();

        $router->addRoute('gallery/index', new Zend_Controller_Router_Route(
            '/gallery/:id/:page', array(
            'action' => 'index',
            'controller' => 'index',
            'module' => 'gallery',
            'id' => 0,
            'page' => 1
        ),
            array(
                'page' => '\d+',
                'id' => '\d+'
            )
        ));

        return $router;
    }

}