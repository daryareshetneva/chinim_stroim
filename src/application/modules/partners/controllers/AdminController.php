<?php

class Partners_AdminController extends Zend_Controller_Action{

    protected $_partner;
    
    public function init(){
        $this->_helper->layout->setLayout('admin');
        $action = $this->_request->getActionName();
 
        if (in_array($action, array('edit', 'delete'))) {
            $partnersTable = new Partners_Model_DbTable_Partners();
            $this->_partner = $partnersTable->find($this->_getParam('id'))->current();
            if (!$this->_partner) {
                $this->_reviewsNotFound();
            }
        }  

        $this->view->assign('action', $action);
    }
        
    public function indexAction() {
        $page = $this->_request->getParam('page', 1);
        $partnersTable = new Partners_Model_DbTable_Partners();

	    $items = $partnersTable->getAll();

	    $paginator = Zend_Paginator::factory($items);
	    $paginator->setItemCountPerPage(20);
	    $paginator->setCurrentPageNumber($page);

	    $this->view->assign('paginator', $paginator);
    }
    
    public function addAction() {
        $this->_helper->viewRenderer('show-form');
        $partnersTable = new Partners_Model_DbTable_Partners();
        $partner = $partnersTable->createRow();
        
        $form = new Partners_Form_Partner($partner);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $imageHelper = new Partners_Model_Images();
                $formData['photo'] = $imageHelper->filter($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
                $partner->setFromArray($formData);
                $partner->save();
                $this->_helper->redirector('index', 'admin', 'partners');
            }
        }
        
        $this->view->assign('form', $form);
    }
    
    public function editAction() {
        $this->_helper->viewRenderer('show-form');
        $form = new Partners_Form_Partner($this->_partner);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {

                if (!empty($_FILES['photo']['tmp_name'])) {
                    $imageHelper = new Partners_Model_Images();
                    $formData['photo'] = $imageHelper->filter($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
                } else {
                    $formData['photo'] = $this->_partner->photo;
                }

                $this->_partner->setFromArray($formData);
                $this->_partner->save();
                $this->_helper->redirector('index', 'admin', 'partners');
            }
        }
        
        $this->view->assign('form', $form);
    }
        
    public function deleteAction() {
        $this->_partner->delete();
        
        $this->_helper->flashMessenger->setNamespace('messages')
                                      ->addMessage('Элемент удален');
        
        $this->_helper->redirector('index', 'admin', 'partners');
    }

    
    protected function _partnerNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
							          ->addMessage('Элемент не найден');
        
        $this->_helper->redirector('index', 'admin', 'partners');
    }
}
