<?php

class Reviews_IndexController extends ItRocks_Controller_Action{
       
    public function indexAction(){
        $reviewsCount = $this->_request->getParam('reviewsCount', 0);

        $reviewsTable = new Reviews_Model_DbTable_Reviews();
        $reviews = $reviewsTable->getLimit($reviewsCount);
        $this->view->assign('multiplator', $reviews);
        $this->view->assign('reviewsCount', $reviewsCount);
    }

    public function sliderAction() {
//        $reviewsTable = new Reviews_Model_DbTable_Reviews();
//
//        $reviews = $reviewsTable->getLimit(9);
//
//        $this->view->assign('items', $reviews);
    }

    public function reviewsItemAction() {

    }

//    public function showAction() {
//        $id = $this->_request->getParam('id');
//
//        $reviewsTable = new Reviews_Model_DbTable_Reviews();
//        $review = $reviewsTable->find($id)->current();
//        if (!$review) {
//            $this->_helper->redirector('home', 'index', 'static');
//        }
//
//        $this->view->assign('review', $review);
//    }

    public function reviewsHomeAction() {
        $reviewsTable = new Reviews_Model_DbTable_Reviews();
        $reviews = $reviewsTable->getLimit(3);

        $this->view->assign('multiplator', $reviews);
    }
}
