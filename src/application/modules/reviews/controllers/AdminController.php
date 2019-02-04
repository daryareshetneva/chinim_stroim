<?php

class Reviews_AdminController extends Zend_Controller_Action{

    protected $_review;    
    
    public function init(){
        $this->_helper->layout->setLayout('admin');
        $action = $this->_request->getActionName();
 
        if (in_array($action, array('edit', 'delete'))) {
            $reviewsTable = new Reviews_Model_DbTable_Reviews();     
            $this->_review = $reviewsTable->find($this->_getParam('id'))->current();
            if (!$this->_review) $this->_reviewsNotFound();
        }  

        $this->view->assign('action', $action);
    }
        
    public function indexAction() {
        $page = $this->_request->getParam('page', 1);
        $reviewsTable = new Reviews_Model_DbTable_Reviews();
        
	    $reviews = $reviewsTable->getAll();

	    $paginator = Zend_Paginator::factory($reviews);
	    $paginator->setItemCountPerPage(20);
	    $paginator->setCurrentPageNumber($page);

	    $this->view->assign('paginator', $paginator);
    }
    
    public function addAction() {
        $this->_helper->viewRenderer('show-form');
        $reviewsTable = new Reviews_Model_DbTable_Reviews();
        $review = $reviewsTable->createRow();
        
        $form = new Reviews_Form_Review($review);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                if (!empty($_FILES['photo']['tmp_name'])) {
                    $imageHelper = new Reviews_Model_Images();
                    $formData['photo'] = $imageHelper->filter($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
                }

                $formData['reviewDate'] = date('Y-m-d H:i:s', strtotime($formData['date']));

                $review->setFromArray($formData);
                $review->save();
                $this->_helper->redirector('index', 'admin', 'reviews');
            }
        }
        
        $this->view->assign('form', $form);
    }
    
    public function editAction() {
        $this->_helper->viewRenderer('show-form');
        $form = new Reviews_Form_Review($this->_review);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $formData['reviewDate'] = date('Y-m-d H:i:s', strtotime($formData['date']));

                if (!empty($_FILES['photo']['tmp_name'])) {
                    $imageHelper = new Reviews_Model_Images();
                    $formData['photo'] = $imageHelper->filter($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
                } else {
                    $formData['photo'] = $this->_review->photo;
                }

                $this->_review->setFromArray($formData);
                $this->_review->save();
                $this->_helper->redirector('index', 'admin', 'reviews');
            }
        }
        
        $this->view->assign('form', $form);
    }
        
    public function deleteAction() {
        $this->_review->delete();
        
        $this->_helper->flashMessenger->setNamespace('messages')
                                      ->addMessage($this->view->translate('reviewDeletedSuccess'));
        
        $this->_helper->redirector('index', 'admin', 'reviews');
    }
    

    
    protected function _reviewsNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
							          ->addMessage($this->view->translate('notFound'));
        
        $this->_helper->redirector('index', 'admin', 'reviews');
    }
}
