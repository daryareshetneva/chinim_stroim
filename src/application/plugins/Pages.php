<?php

class Plugin_Pages extends Zend_Controller_Plugin_Abstract {

    protected $_bootstrap = null;
    protected $_params = array();
    protected $_action = null;
    protected $_module = 'default';
    protected $_controller = null;
    protected $_modules = [];

    public function __construct($bootstrap) {
        $this->_bootstrap = $bootstrap;
        $this->_modules = Bootstrap::getModuleList();
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        $view = $this->_bootstrap->view;
        $this->_params = $request->getParams();
        $this->_action = $request->getActionName();
        $this->_controller = $request->getControllerName();
        $this->_module = $request->getModuleName();

        $pages = $this->_getPages();
        $navigation = new Zend_Navigation($pages);

        $view->navigation()
            ->setContainer($navigation)
            ->setTranslator($this->_bootstrap->translate);
    }

    protected function _getPages() {
        $pages = array(
            array(
                'label' => 'menuHome',
                'controller' => 'index',
                'action' => 'home',
                'module' => 'static',
                'route' => 'home',
                'pages' => array(
                    [
                        'label' => 'menuFeedbackSuccess',
                        'controller' => 'index',
                        'action' => 'feedback-success',
                        'module' => 'default',
                        'route' => '/feedback-success'
                    ],
                    [
                        'label' => 'menuUserAuth',
                        'controller' => 'index',
                        'action' => 'login',
                        'module' => 'user',
                        'route' => '/user/index/login'
                    ],
                    [
                        'label' => 'menuUserRegister',
                        'controller' => 'registration',
                        'action' => 'index',
                        'module' => 'user',
                        'route' => '/user/registration/index'
                    ],
                    [
                        'label' => 'menuUserForget',
                        'controller' => 'registration',
                        'action' => 'forget',
                        'module' => 'user',
                        'route' => '/user/registration/forget'
                    ],
                )
            )
        );

        if ('static' == $this->_module && 'index' == $this->_action && 'admin' != $this->_controller) {
            $pages[0]['pages'][] = $this->_getStaticPagesBreadCrumbs();
        }
        if ($this->_modules['News']){
            $pages[0]['pages'][] = $this->_getNewsBreadCrumbs();
        }
        if ($this->_modules['Gallery']){
            $pages[0]['pages'][] = $this->_getGalleryBreadCrumbs();
        }
        if ($this->_modules['Blog']){
            $pages[0]['pages'][] = $this->_getBlogBreadCrumbs();
        }

        if ($this->_modules['Shop']){
            $pages[0]['pages'][] = $this->_getShopBreadCrumbs();
            $pages[0]['pages'][] = [
                        'label' => 'menuCart',
                        'controller' => 'index',
                        'action' => 'cart',
                        'module' => 'shop',
                        'route' => 'cart',
                        'pages' => [
                            [
                                'label' => 'menuCartOrder',
                                'controller' => 'index',
                                'action' => 'order',
                                'module' => 'shop',
                                'route' => 'cart/order',
                            ]
                        ]
                    ];
            $pages[0]['pages'][] = [
                        'label' => 'orderSuccessMessage',
                        'controller' => 'index',
                        'action' => 'order-success',
                        'module' => 'shop',
                    ];
        }


        return $pages;
    }
    /**
     * Вовзращает массив хлебных крошек для модуля news
     * TODO! Переделать label для экшена show-by-tag
     * @return array
     */
    private function _getNewsBreadCrumbs() {
        $currentPage = (isset($this->_params['page'])) ? $this->_params['page'] : 1;
        $pages = [
            'label' => 'menuNews',
            'controller' => 'index',
            'action' => 'index',
            'module' => 'news',
            'params' => ['page' => $currentPage],
            'route' => '/news/index/index',
            'pages' => [

            ]
        ];

        if ('news' == $this->_module && 'show' == $this->_action && !empty($this->_params['alias'])) {
            $newsTable = new News_Model_DbTable_Table();
            $news = $newsTable->getByAlias($this->_params['alias']);
            if ($news) {
                $pages['pages'][] = [
                    'label' => $news->title,
                    'controller' => 'index',
                    'action' => 'show',
                    'module' => 'news',
                    'route' => '/news/show',
                    'params' => ['alias' => $news->alias]
                ];
            }
        }

        if ('news' == $this->_module && 'show-by-tag' == $this->_action && !empty($this->_params['tag'])) {
            $tag        = new Model_DbTable_Tags();
            $tagName    = $tag->getTagById($this->_params['tag']);
            $pages['pages'][] = [
                'label' => 'Просмотр записей по тэгу ' . $tagName['name'],
                'controller' => 'index',
                'action' => 'show-by-tag',
                'module' => 'news',
                'route' => '/news/showtag'
            ];
        }

        return $pages;
    }

    private function _getGalleryBreadCrumbs() {
        $currentPage = (isset($this->_params['page'])) ? $this->_params['page'] : 1;
        $currentId = (isset($this->_params['id'])) ? $this->_params['id'] : 0;
        $pages = [
            'label' => 'menuGallery',
            'controller' => 'index',
            'action' => 'index',
            'module' => 'gallery',
            'params' => [
                'page' => $currentPage,
                'id' => $currentId
            ],
            'route' => 'gallery/index',
            'pages' => []
        ];

        if ('gallery' == $this->_module && 'index' == $this->_action && $this->_params['id'] > 0) {
            $table = new Gallery_Model_DbTable_Category();
            $title = $table->getCategoryTitleById($this->_params['id']);
            if (!empty($title)) {
                $pages['pages'][] = [
                    'label' => $title,
                    'controller' => 'index',
                    'action' => 'index',
                    'module' => 'gallery',
                    'route' => 'gallery/index',
                    'params' => []
                ];
            }
        }
        return $pages;
    }

    /**
     * Вовзращает массив хлебных крошек для модуля blog
     * TODO! Переделать label для экшена show-by-tag
     * @return array
     */
    private function _getBlogBreadCrumbs() {
        $currentPage = (isset($this->_params['page'])) ? $this->_params['page'] : null;
        $pages = [
            'label' => 'menuBlog',
            'controller' => 'index',
            'action' => 'index',
            'module' => 'blog',
            'params' => ['page' => $currentPage],
            'route' => 'blogPages',
            'pages' => [

            ]
        ];

        if ('blog' == $this->_module && 'show' == $this->_action && !empty($this->_params['alias'])) {
            $blogTable = new Blog_Model_DbTable_Blog;
            $blogItem = $blogTable->getByAlias($this->_params['alias']);
            if ($blogItem) {
                $pages['pages'][] = [
                    'label' => $blogItem->title,
                    'controller' => 'index',
                    'action' => 'show',
                    'module' => 'blog',
                    'route' => '/blog/show',
                    'params' => ['alias' => $blogItem->alias]
                ];
            }
        }

        if ('blog' == $this->_module && 'show-by-tag' == $this->_action && !empty($this->_params['tag'])) {
            $tag        = new Model_DbTable_Tags();
            $tagName    = $tag->getTagById($this->_params['tag']);
            $pages['pages'][] = [
                'label' => 'Просмотр записей по тэгу ' . $tagName['name'],
                'controller' => 'index',
                'action' => 'show-by-tag',
                'module' => 'blog',
                'route' => '/blog/showtag'
            ];
        }

        return $pages;
    }

    private function _getStaticPagesBreadCrumbs() {
        $pages = [];
        if ('static' == $this->_module && 'index' == $this->_action && !empty($this->_params['alias']) && 'admin' != $this->_controller) {
            $staticTable = new Static_Model_DbTable_Static();
            $page = $staticTable->find($this->_params['alias'])->current();
            if ($page) {
                $pages = [
                    'label' => $page->title,
                    'controller' => 'index',
                    'action' => 'index',
                    'module' => 'static',
                    'params' => ['alias' => $page->alias],
                    'route' => '/static/index/index'
                ];
            }

        }
        return $pages;
    }

    private function _getUserBreadCrumbs() {
        $pages = [
            'label' => 'menuUserAuth',
            'controller' => 'index',
            'action' => 'login',
            'module' => 'user',
            'route' => '/user/index/login'
        ];

        return $pages;
    }

    private function _getShopBreadCrumbs() {
        $currentPage = (isset($this->_params['page'])) ? $this->_params['page'] : null;
        $alias = (isset($this->_params['alias'])) ? $this->_params['alias'] : 'catalog';
        $productPage = [];
        $product = null;

        $pages = [
            'label' => 'shopCatalog',
            'controller' => 'index',
            'action' => 'index',
            'module' => 'shop',
            'params' => ['page' => $currentPage, 'alias' => 'catalog'],
            'route' => 'shop/index',
            'pages' => [

            ],
        ];
        if ('shop' == $this->_module) {
            if ('product' == $this->_action) {
                $shopProductsTable = new Shop_Model_DbTable_Products();
                $product = $shopProductsTable->getByAlias($alias);
                $productPage = [
                    'label' => $product->title,
                    'controller' => 'index',
                    'action' => 'product',
                    'module' => 'shop',
                    'params' => ['alias' => $product->alias],
                    'route' => '/shop/product',
                ];
            }

            if ('catalog' !== $alias) {
                $shopCategoriesTable = new Shop_Model_DbTable_Categories();
                if (empty($productPage)) {
                    $currentCategory = $shopCategoriesTable->getByAlias($alias);
                } else {
                    $currentCategory = $shopCategoriesTable->find($product->categoryId)->current();
                }
                $parentId = $currentCategory->parentId;

                $subPages = [
                    'label' => $currentCategory->title,
                    'controller' => 'index',
                    'action' => 'index',
                    'module' => 'shop',
                    'params' => ['page' => $currentPage, 'alias' => $currentCategory->alias],
                    'route' => 'shop/index',
                ];
                if (!empty($productPage)) {
                    $subPages['pages'][] = $productPage;
                }

                while ($parentId != 0) {
                    $cat = $shopCategoriesTable->find($parentId)->current();
                    $subPages = [
                        'label' => $cat->title,
                        'controller' => 'index',
                        'action' => 'index',
                        'module' => 'shop',
                        'params' => ['page' => $currentPage, 'alias' => $cat->alias],
                        'route' => 'shop/index',
                        'pages' => [
                            $subPages
                        ]
                    ];
                    $parentId = $cat->parentId;
                }

                $pages['pages'][] = $subPages;
            }
        }
        return $pages;
    }

}