<?php

class Zend_View_Helper_PageTitles extends Zend_View_Helper_Abstract {
    
    public function pageTitles() {
        $request = Zend_Controller_Front::getInstance()
            ->getRequest();
        $action = $request->getActionName();
        $controller = $request->getControllerName();
        $module = $request->getModuleName();
        $params = $request->getParams();
        
        $title = '';
        if ('static' == $module && $action != 'home') {
            $alias = $params = $request->getParam('alias');
            $pagesTable = new Static_Model_DbTable_Static();
            $title = $pagesTable->getPageTitleByAlias($alias);
        } else if ('shop' == $module) {
            $title = $this->_getShopTitle($controller, $action, $params);
        } else if ('news' == $module) {
            $title = $this->_getNewsTitle($controller, $action, $params);
        } else if ('gallery' == $module) {
            $title = $this->_getGalleryTitle($controller, $action, $params);
        } else if ('blog' == $module) {
            $title = $this->_getBlogsTitle($controller, $action, $params);
        } else {
            $tagsModel = new Model_Tags();
            $tags = $tagsModel->getTagsByMCA($module, $controller, $action);
            $title = $tags['title'];
        }
        return $title;
    }

    private function _getShopTitle($controller, $action, $params) {
        $title = '';
        if ('index' == $action) {
            if (isset($params['alias'])) {
                $shopCategoriesTable = new Shop_Model_DbTable_Categories();
                $title = $shopCategoriesTable->getTitleByAlias($params['alias']);
            }

            if (empty($title)) {
                $title = $this->view->translate('shopCatalog');
            }

        } else if ('product' == $action) {
            if (isset($params['alias'])) {
                $shopProductsTable = new Shop_Model_DbTable_Products();
                $title = $shopProductsTable->getTitleByAlias($params['alias']);
            }

            if (empty($title)) {
                $title = $this->view->translate('shopProductPage');
            }
        } else if ('cart' == $action) {
            $title = $this->view->translate('cart');
        } else if ('order' == $action) {
            $title = $this->view->translate('menuCartOrder');
        } else if ('order-success' == $action) {
            $title = $this->view->translate('orderSuccessMessage');
        }
        return $title;
    }

    private function _getNewsTitle($controller, $action, $params) {
        $title = '';
        $newsTable = new News_Model_DbTable_Table();
        if ($action == 'show') {
            $title = $newsTable->getTitleByAlias($params['alias']);
        } else if ('show-by-tag' == $action) {
            $tagsTable = new Model_DbTable_Tags();
            $title = sprintf($this->view->translate('newsShowByTag'), $tagsTable->getNameById($params['tag']));
        }
        return $title;
    }

    private function _getGalleryTitle($controller, $action, $params) {
        $title = '';
        $table = new Gallery_Model_DbTable_Category();
        if ($params['id'] > 1){
            if ($action == 'index') {
                $title = $table->getCategoryTitleById($params['id']);
            }
        } else {
            $title = $this->view->translate('gallery');
        }
        return $title;
    }

    private function _getBlogsTitle($controller, $action, $params) {
        $title = '';
        $blogsTable = new Blog_Model_DbTable_Blog();
        if ($action == 'show') {
            $title = $blogsTable->getTitleByAlias($params['alias']);
        } else if ('show-by-tag' == $action) {
            $tagsTable = new Model_DbTable_Tags();
            $title = sprintf($this->view->translate('blogShowByTag'), $tagsTable->getNameById($params['tag']));
        }
        return $title;
    }
}