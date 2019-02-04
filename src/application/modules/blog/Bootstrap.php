<?php

class Blog_Bootstrap extends Zend_Application_Module_Bootstrap{

  protected function _initRouter(){
    $frontController = Zend_Controller_Front::getInstance();
    $router = $frontController->getRouter();

      $router->addRoute('/blog/show', new Zend_Controller_Router_Route(
          '/blog/:alias', array(
              'module' => 'blog',
              'controller' => 'index',
              'action' => 'show',
              'alias' => null
          )
      ));

      $router->addRoute('/blog/index/index', new Zend_Controller_Router_Route(
          '/blog/', array(
              'module' => 'blog',
              'controller' => 'index',
              'action' => 'index'
          )
      ));

      $router->addRoute('blogPages', new Zend_Controller_Router_Route(
          '/blog/page/:page', array(
              'module' => 'blog',
              'controller' => 'index',
              'action' => 'index',
              'page' => null
          )
      ));

      $router->addRoute('blogAdmin', new Zend_Controller_Router_Route(
          '/blog/admin', array(
              'module' => 'blog',
              'controller' => 'admin',
              'action' => 'index'
          )
      ));
    
    return $router;
  }
    
}
