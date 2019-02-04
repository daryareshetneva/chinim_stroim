<?php

class News_Bootstrap extends Zend_Application_Module_Bootstrap{

    protected function _initRouter(){
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();

        $router->addRoute('/news/index/index', new Zend_Controller_Router_Route(
                '/news/page/:page', array(
                    'module' => 'news',
                    'controller' => 'index',
                    'action' => 'index',
                    'page' => null
                ),
                array('page' => '\d+')
        ));

        $router->addRoute('/news/show', new Zend_Controller_Router_Route(
            '/news/:alias', array(
                'module' => 'news',
                'controller' => 'index',
                'action' => 'show',
                'alias' => null
            )
        ));

        $router->addRoute('/news/showtag', new Zend_Controller_Router_Route(
            '/news/showtag/:tag', array(
            'module' => 'news',
            'controller' => 'index',
            'action' => 'show-by-tag'
        ),
            array('tag' => '\d+')
        ));

        $router->addRoute('newsAdmin', new Zend_Controller_Router_Route(
            '/news/admin', array(
                'module' => 'news',
                'controller' => 'admin',
                'action' => 'index'
            )
        ));

        $router->addRoute(
            "/", new Zend_Controller_Router_Route(
            '/',
            array(
                'module' => 'static',
                'controller' => 'index',
                'action' => 'home'
            )));
        
        return $router;
    }
    
}