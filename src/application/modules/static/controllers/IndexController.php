<?php

class Static_IndexController extends ItRocks_Controller_Action {

    protected $_alias;
    protected $_page;

    public function init() {
        $staticModel = new Static_Model_DbTable_Static();
        $params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        $alias = $this->_getParam('alias', 'home');

        $page = $staticModel->find($alias)->current();

        if ($alias && $page) {
            $this->_page = $page;
            $this->_alias = $alias;
        } else {
            $this->error();
        }

        if ($params['action'] != 'home' && $alias == 'home') {
            $this->_helper->redirector('home', 'index', 'static');
        }

        if ($params['action'] != 'home') {
            $this->_addMeta($page['metaTitle'], $page['metaDescription']);
        }
    }

    public function indexAction() {
        if ($this->_alias == 'home') {
            $this->_helper->viewRenderer('home');
        }
//        if ($this->_alias == 'how-to-make-order') {
//            $this->_helper->viewRenderer('how-order');
//        }
        if ($this->_alias == 'about') {
            $this->_helper->viewRenderer('about');
        }
        if ($this->_alias == 'contacts') {
            $settingsModel = new Model_Settings();
            $data = $settingsModel->getSettings(['address', 'phone', 'email']);
            $this->view->assign('data', $data);
            $this->_helper->viewRenderer('contacts');
        }
        $this->view->page = $this->_page;
    }

    public function homeAction() {
        $modules = Bootstrap::getModuleList();
        if ($modules['News']){
            $newsModel = new News_Model_DbTable_Table;
            $news = $newsModel->getLastTenNews(3);
            $this->view->assign('news', $news);
            $this->view->assign('newsActive', true);
        }
        $this->view->page = $this->_page;
        //$this->_addTags('static', 'index', 'home');
    }

    public function contactsAction() {
//        $modules = Bootstrap::getModuleList();
//        if ($modules['News']){
//            $newsModel = new News_Model_DbTable_Table;
//            $news = $newsModel->getLastTenNews(3);
//            $this->view->assign('news', $news);
//            $this->view->assign('newsActive', true);
//        }
//        $this->view->page = $this->_page;
//        $this->_addTags('static', 'index', 'contacts');
    }

    public function aboutAction() {
//        $modules = Bootstrap::getModuleList();
//        if ($modules['News']){
//            $newsModel = new News_Model_DbTable_Table;
//            $news = $newsModel->getLastTenNews(3);
//            $this->view->assign('news', $news);
//            $this->view->assign('newsActive', true);
//        }
//        $this->view->page = $this->_page;
//        $this->_addTags('static', 'index', 'home');
    }
}
