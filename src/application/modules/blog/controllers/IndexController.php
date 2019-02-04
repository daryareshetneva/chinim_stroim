<?php

class Blog_IndexController extends ItRocks_Controller_Action
{
    public function init()
    {
        $tagsModel = new Model_Tags();
        $tags = $tagsModel->getTagsByMCA(
            $this->_request->getModuleName(),
            $this->_request->getControllerName(),
            $this->_request->getActionName()
        );
        $this->_addMeta($tags['metaTitle'], $tags['metaDescription']);
        $this->view->assign('title', $tags['title']);
    }

    public function indexAction()
    {
        $page = $this->_request->getParam('page', 1);
        $diyTable = new Blog_Model_DbTable_Blog;
        $items = $diyTable->getAll();

        $paginator = Zend_Paginator::factory($items);
        $paginator->setItemCountPerPage(3); // Пагинация
        $paginator->setCurrentPageNumber($page);
        $currentPageNumber = $paginator->getCurrentPageNumber();
        $requestPageNumber = (int)$this->_request->getParam('page', 1);

        if (($requestPageNumber <= 0) || ($currentPageNumber < $requestPageNumber) || (!is_int($requestPageNumber))) {
            $this->error404();
        }
        $this->view->assign('paginator', $paginator);
        $this->view->assign('helper', $this->_helper->textHelper);
    }

    public function showAction()
    {
        $itemAlias  = $this->_request->getParam('alias');
        $diy = null;
        if (!empty($itemAlias)){
            $diyTable   = new Blog_Model_DbTable_Blog;
            $diy        = $diyTable->getByAlias($itemAlias);
        }

        if ($diy){
            $this->view->headTitle()->prepend($diy->metaTitle);
            $this->view->headMeta()->setName('description',  $diy->metaDescription);
            $this->view->headMeta()->setName('keywords', $diy->metaKeywords);

           // $datatim = new DateTime('Y-m-d H:i:s');

            $this->view->assign('item', $diy);
        } else{
            $this->error404();
        }

    }
}