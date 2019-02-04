<?php

class Faq_Bootstrap extends Zend_Application_Module_Bootstrap{

    protected function _initRouter(){
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();
        
        $router->addRoute('faq', new Zend_Controller_Router_Route(
                '/faq/page/:page', array(
                    'module' => 'faq',
                    'controller' => 'index',
                    'action' => 'index',
                    'page' => 1
                )));
        
        $router->addRoute('faqAdd', new Zend_Controller_Router_Route(
            '/faq/add', array(
                'module' => 'faq',
                'controller' => 'index',
                'action' => 'add'
            )));
        
        return $router;
    }

}