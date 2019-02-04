<?php

class ItRocks_Controller_Action extends Zend_Controller_Action {
    
    /*     * **********************************
     * 	    VIEW
     * ********************************** */

    protected function assign($assingArray) {
        foreach ($assingArray as $viewName => $item) {
            $this->view->assign($viewName, $item);
        }
    }

    protected function paginatorInit($items, $countPerPage, $page) {
        $paginator = Zend_Paginator::factory($items);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($page);

        $this->view->assign('paginator', $paginator);
    }

    /*     * **********************************
     * 	    TAGS
     * ********************************** */

    public function addTagsAuto() {
        $params = $this->getAllParams();
        $this->_addTags($params['module'], $params['controller'],
                $params['action']);
    }

    protected function _addTags($module, $controller, $action) {
        $tagsModel = new Model_Tags();
        $tags = $tagsModel->getTagsByMCA($module, $controller, $action);
        $this->_addMeta($tags['metaTitle'], $tags['metaDescription']);
        if ($tags['title'] != '') {
            $this->view->assign('title', $tags['title']);
        }
    }

    protected function _addMeta($metaTitle, $metaDescription = null, $metaKeywords = null) {
        $setting = new Model_Settings();
        $headData = $setting->getSettings(['title','keywords', 'description']);
        $this->view->headTitle()->setSeparator(' | ');

        if (!empty($metaTitle)){
            $this->view->headTitle()->set($metaTitle);
            $this->view->headTitle($headData['title']);
        } else {
            $this->view->headTitle()->set($headData['title']);
        }
        if (!empty($metaDescription)) {
            $this->view->headMeta()->setName('description', $metaDescription);
        } else {
            $this->view->headMeta()->setName('description', $headData['description']);
        }
        if (!empty($metaKeywords)) {
            $this->view->headMeta()->setName('metaKeywords', $metaKeywords);
        } else {
            $this->view->headMeta()->setName('keywords', $headData['keywords']);
        }
    }

    /*     * **********************************
     * 	    FLASH
     * ********************************** */

    protected function flashMessage($type, $msg) {
        if (!in_array($type, array(
            'messages', 'errorMessages', 'warningMessages', 'blueMessages')
        )) {
            return false;
        }

        $message = $this->view->translate($msg);

        $this->getHelper('flashMessenger')
                ->setNamespace($type)
                ->addMessage($message);
    }

    /*     * **********************************
     * 	    REDIRECTOR
     * ********************************** */

    protected function _goToHome() {
        $this->_helper->redirector('home', 'index', 'static');
    }

    public function error() {
        $this->_helper->redirector('error', 'error', 'default');
    }

    //    ERRORS
    public function error404(){
        $this->getResponse()->setHttpResponseCode(404);
        $this->view->setBasePath(APPLICATION_PATH . '/views/');
        $this->_helper->viewRenderer->renderBySpec('error', ['module' => 'default', 'controller' => 'error']);
        $this->_addMeta('404! Страница не существует');
    }

}
