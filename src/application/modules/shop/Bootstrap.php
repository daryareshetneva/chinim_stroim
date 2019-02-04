<?php

class Shop_Bootstrap extends Zend_Application_Module_Bootstrap {

    protected function _initRouter() {
        $router = Zend_Controller_Front::getInstance()->getRouter();

        $router->addRoute('shop/index', new Zend_Controller_Router_Route(
            '/shop/:alias', array(
            'action' => 'index',
            'controller' => 'index',
            'module' => 'shop',
            'alias' => 'catalog'
        )));

        $router->addRoute('shop/page', new Zend_Controller_Router_Route(
            '/shop/:alias/:page', array(
            'module' => 'shop',
            'controller' => 'index',
            'action' => 'index',
            'alias' => 'catalog',
            'page' => null
        ),
            array('page' => '\d+')
        ));

        $router->addRoute('/shop/product', new Zend_Controller_Router_Route(
            '/shop/product/:alias', array(
            'action' => 'product',
            'controller' => 'index',
            'module' => 'shop',
            'alias' => null
        )));

        $router->addRoute('cart', new Zend_Controller_Router_Route(
            '/cart', array(
            'action' => 'cart',
            'controller' => 'index',
            'module' => 'shop'
        )));

        $router->addRoute('cart/order', new Zend_Controller_Router_Route(
            '/cart/order', array(
            'action' => 'order',
            'controller' => 'index',
            'module' => 'shop'
        )));

        $router->addRoute('admin/orders', new Zend_Controller_Router_Route(
            '/admin/orders/:status/:page', array(
            'module' => 'shop',
            'controller' => 'admin-order',
            'action' => 'index',
            'status' => 0,
            'page' => 1
        ),
            array(
                'page' => '\d+',
                'status' => '\d+'
            )
        ));

        $router->addRoute('admin/order', new Zend_Controller_Router_Route(
            '/admin/order/:id', array(
            'module' => 'shop',
            'controller' => 'admin-order',
            'action' => 'view-order',
            'id' => 0,
        ),
            array(
                'id' => '\d+'
            )
        ));

        $router->addRoute('admin/admin-categories', new Zend_Controller_Router_Route(
            '/shop/admin-categories', array(
            'module' => 'shop',
            'controller' => 'admin-categories',
            'action' => 'index'
        )));

        $router->addRoute('admin/admin-filters', new Zend_Controller_Router_Route(
            '/shop/admin-filters', array(
            'module' => 'shop',
            'controller' => 'admin-filters',
            'action' => 'index'
        )));

        $router->addRoute('admin/admin-products', new Zend_Controller_Router_Route(
            '/shop/admin-products', array(
            'module' => 'shop',
            'controller' => 'admin-products',
            'action' => 'index'
        )));

        $router->addRoute('admin/admin-import', new Zend_Controller_Router_Route(
            '/shop/admin-import', array(
            'module' => 'shop',
            'controller' => 'admin-import',
            'action' => 'index'
        )));

        return $router;
    }

}