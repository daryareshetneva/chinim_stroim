<?php

class Portfolio_IndexController extends ItRocks_Controller_Action  {
    
    public function indexAction() {
//        $page = $this->_request->getParam('page', 1);
//        $reviewsTable = new Reviews_Model_DbTable_Reviews();
//
//        $reviews = $reviewsTable->getAll();
//
//        $paginator = Zend_Paginator::factory($reviews);
//        $paginator->setItemCountPerPage(7);
//        $paginator->setCurrentPageNumber($page);
//
//        $this->view->assign('paginator', $paginator);
//        $this->view->assign('page', $page);

        $page = $this->_request->getParam('page', 1);
        $portfolioTable = new Portfolio_Model_DbTable_Portfolio();

        $portfolios = $portfolioTable->getAll();

        $paginator = Zend_Paginator::factory($portfolios);
        $paginator->setItemCountPerPage(6);
        $paginator->setCurrentPageNumber($page);

        $this->view->assign('paginator', $paginator);
        $this->view->assign('page', $page);

//        $catalogId = $this->_request->getParam('catalogId', 0);
//        $itemId = $this->_request->getParam('itemId', 0);
//
//        $portfolioModel = new Portfolio_Model_Portfolio();
//        $items = $portfolioModel->getAll();
//
//
//        $this->view->assign('items', $items);
//        $this->view->assign('catalogId', $catalogId);
//        $this->view->assign('itemId', $itemId);
    }
  
    public function portfolioAction() {
        $portfolioModel = new Portfolio_Model_Portfolio();

        $items = $portfolioModel->getAll();

        $this->view->assign('items', $items);
    }
  
    public function showAction() {
        $alias = $this->_request->getParam('alias');
        
        $table = new Portfolio_Model_Portfolio();
        $item = $table->getPortfolioByAlias($alias);     
        
        $portfolioCommentsTable = new Portfolio_Model_DbTable_PortfolioComments();
        $form = new Portfolio_Form_Comment();
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $data = array(
                    'date' => date('Y-m-d'),
                    'username' => $formData['username'],
                    'email' => $formData['email'],
                    'new' => 1,
                    'comment' => $formData['comment'],
                    'status' => 0,
                    'portfolioId' => $item['id']
                );
                $comment = $portfolioCommentsTable->fetchNew();
                $comment->setFromArray($data);
                $comment->save();
                
                $this->_helper->redirector('show', 'index', 'portfolio', array('alias' => $item['alias']));
            }
        }
        
        $this->view->headMeta()->setName('description', stripslashes($item['metaDescription']));
        $this->addTitle($item['metaTitle']);
        $this->view->assign('item', $item);
        $this->view->assign('comments', $portfolioCommentsTable->getCommentsByPortfolioId($item['id']));
        $this->view->assign('form', $form);
    }

    public function portfolioHomeAction() {
        $portfolioTable = new Portfolio_Model_DbTable_Portfolio();
        $portfolios = $portfolioTable->getLimit(3);

        $this->view->assign('multiplator', $portfolios);
    }

    public function portfolioHomeItemAction() {
        $currentPortfolioId = $this->_request->getParam('currentPortfolioId');

        $portfolioImagesTable = new Portfolio_Model_DbTable_PortfolioImages();
        $mainImage = $portfolioImagesTable->getMainImageOfPortfolioById($currentPortfolioId);

        $this->view->assign('mainImage', $mainImage);
    }

    public function indexItemAction() {
        $currentPortfolio = $this->_request->getParam('currentPortfolio');

        $portfolioImagesTable = new Portfolio_Model_DbTable_PortfolioImages();
        $mainImage = $portfolioImagesTable->getMainImageOfPortfolioById($currentPortfolio['id']);

        $this->view->assign('mainImage', $mainImage);
        $this->view->assign('currentPortfolio', $currentPortfolio);
    }

    public function portfolioExampleAction() {
        $currentPortfolioId = $this->_request->getParam('portfolioId');

        $portfolioImagesTable = new Portfolio_Model_DbTable_PortfolioImages();
        $portfolioTable = new Portfolio_Model_DbTable_Portfolio();

        $portfolio = $portfolioTable->getById($currentPortfolioId);
        $images = $portfolioImagesTable->getImagesByPortfolioId($currentPortfolioId);

        $this->view->assign('portfolio', $portfolio);
        $this->view->assign('images', $images);
    }

    private function addTitle($title) {
        $this->view->headTitle()->setAutoEscape(false);
        $this->view->headTitle()->set(stripslashes($title));
    }


}
