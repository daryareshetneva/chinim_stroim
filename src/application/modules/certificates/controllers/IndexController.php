<?php

class Certificates_IndexController extends ItRocks_Controller_Action{

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
        $certificatesTable = new Certificates_Model_DbTable_Certificates();
        $categories = $certificatesTable->getAll();

        $this->view->assign('multiplator', $categories);
//        $alias = $this->_request->getParam('alias');
//        $arr = ['index', 'page', ''];
//        if (!in_array($alias, $arr)) {
//            $this->error404();
//        }
//
//        $page = $this->_request->getParam('page', 1);
//        $paginator = $this->_getCertificatesPaginator($page);
//        $pagesAmount = $paginator->getPages()->pageCount;
//
//
//        $currentPageNumber = $paginator->getCurrentPageNumber();
//        $requestPageNumber = (int)$this->_request->getParam('page', 1);
//        if (($requestPageNumber <= 0) || ($currentPageNumber < $requestPageNumber) || (!is_int($requestPageNumber))) {
//            $this->error404();
//        }
//
//        $this->view->paginator = $paginator;
//        $this->view->assign('pagesAmount', $pagesAmount);
    }

    private function _getCertificatesPaginator($page) {
        $certificates = new Certificates_Model_DbTable_Certificates();
        $allCertificates = $certificates->getAll();

        $paginator = Zend_Paginator::factory($allCertificates);
        $paginator->setItemCountPerPage(5);
        $paginator->setCurrentPageNumber($page);
        return $paginator;
    }
}
