<?php

class User_Bootstrap extends Zend_Application_Module_Bootstrap {

    protected $_router;

    public function __construct($application) {
        parent::__construct($application);

        $frontController = Zend_Controller_Front::getInstance();
        $this->_router = $frontController->getRouter();
    }

    protected function _initRouters() {
        $module = 'user';

        $routerNames = $this->_initDynamicRouter($module);

        $curRouterName = "/{$module}/index/index";
        $this->_router->addRoute($curRouterName,
                new Zend_Controller_Router_Route(
                "/{$module}/index",
                array(
            'module' => $module,
            'controller' => 'index',
            'action' => 'index'
                )
        ));

        $curRouterName = "{$module}/orders";
        $this->_router->addRoute($curRouterName,
            new Zend_Controller_Router_Route(
                "/{$module}/orders/:page",
                array(
                    'module' => $module,
                    'controller' => 'index',
                    'action' => 'orders',
                    'page' => 1
                )
            ));

        $curRouterName = "{$module}/view-order";
        $this->_router->addRoute($curRouterName,
            new Zend_Controller_Router_Route(
                "/{$module}/order/:id",
                array(
                    'module' => $module,
                    'controller' => 'index',
                    'action' => 'view-order',
                    'id' => null
                )
            ));

        $curRouterName = "{$module}/edit-profile";
        $this->_router->addRoute($curRouterName,
            new Zend_Controller_Router_Route(
                "/{$module}/edit",
                array(
                    'module' => $module,
                    'controller' => 'index',
                    'action' => 'edit-profile'
                )
            ));

        $curRouterName = "/{$module}/index/logout";
        $this->_router->addRoute($curRouterName,
                new Zend_Controller_Router_Route(
                "/{$module}/index/logout",
                array(
            'module' => $module,
            'controller' => 'index',
            'action' => 'logout'
                )
        ));

        $curRouterName = "/{$module}/index/login";
        if (!in_array($curRouterName, $routerNames)) {
            $this->_router->addRoute($curRouterName,
                    new Zend_Controller_Router_Route(
                    "/{$module}/login" . "/:leave",
                    array(
                'module' => $module,
                'controller' => 'index',
                'action' => 'login',
                'leave' => null
                    )
            ));
        }
        $curRouterName = "/{$module}/registration/index";
        if (!in_array($curRouterName, $routerNames)) {
            $this->_router->addRoute($curRouterName,
                    new Zend_Controller_Router_Route(
                    "/{$module}/reg",
                    array(
                'module' => $module,
                'controller' => 'registration',
                'action' => 'index'
                    )
            ));
        }
        $curRouterName = "/{$module}/registration/forget";
        if (!in_array($curRouterName, $routerNames)) {
            $this->_router->addRoute($curRouterName,
                    new Zend_Controller_Router_Route(
                    "/{$module}/forget",
                    array(
                'module' => $module,
                'controller' => 'registration',
                'action' => 'forget'
                    )
            ));
        }

        return $this->_router;
    }

    private function _initDynamicRouter($module) {
        $tagsModel = new Model_Tags();
        $tags = $tagsModel->getTagsInBootstrap($module);
        $routerNames = array();

        if ($tags) {
            foreach ($tags as $page) {
                $curRouterName = "/{$module}/{$page['controller']}/{$page['action']}";
                if ($page['alias'] != '') {

                    $curRouterName = "/{$module}/{$page['controller']}/{$page['action']}";
                    if ($curRouterName == "/{$module}/index/login") {
                        $this->_router->addRoute($curRouterName,
                                new Zend_Controller_Router_Route(
                                $page['alias'] . '/:leave',
                                array(
                            'module' => $page['module'],
                            'controller' => $page['controller'],
                            'action' => $page['action'],
                            'leave' => null
                        )));
                    } else {
                        $this->_router->addRoute($curRouterName,
                                new Zend_Controller_Router_Route(
                                $page['alias'],
                                array(
                            'module' => $page['module'],
                            'controller' => $page['controller'],
                            'action' => $page['action']
                        )));
                    }

                    $routerNames[] = $curRouterName;
                }
            }
        }

        return $routerNames;
    }

}
