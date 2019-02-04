<?php

class Specialists_AdminController extends Zend_Controller_Action {

    protected $_specialist;
    
    public function init(){
        $this->_helper->layout->setLayout('admin');
        $action = $this->_request->getActionName();
 
        if (in_array($action, array('edit', 'delete'))) {
            $specialistsTable = new Specialists_Model_DbTable_Specialists();
            $this->_specialist = $specialistsTable->find($this->_getParam('id'))->current();
            if (!$this->_specialist) {
                $this->_specialistNotFound();
            }
        }  

        $this->view->assign('action', $action);
    }
        
    public function indexAction() {
        $page = $this->_request->getParam('page', 1);
        $specialistsTable = new Specialists_Model_DbTable_Specialists();

	    $items = $specialistsTable->getAll();

	    $paginator = Zend_Paginator::factory($items);
	    $paginator->setItemCountPerPage(20);
	    $paginator->setCurrentPageNumber($page);

	    $this->view->assign('paginator', $paginator);
    }
    
    public function addAction() {
        $this->_helper->viewRenderer('show-form');
        $specialistsTable = new Specialists_Model_DbTable_Specialists();
        $specialist = $specialistsTable->createRow();
        
        $form = new Specialists_Form_Specialist($specialist);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $imageHelper = new Specialists_Model_Images();
                $formData['photo'] = $imageHelper->filter($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
                $specialist->setFromArray($formData);
                $specialist->save();
                $this->_helper->redirector('index', 'admin', 'specialists');
            }
        }
        
        $this->view->assign('form', $form);
    }
    
    public function editAction() {
        $this->_helper->viewRenderer('show-form');
        $form = new Specialists_Form_Specialist($this->_specialist);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {

                $imageHelper = new Specialists_Model_Images();
                if (!empty($_FILES['photo']['tmp_name'])) {
                    $formData['photo'] = $imageHelper->filter($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
                } else {
                    $formData['photo'] = $this->_specialist->photo;
                }

                $this->_specialist->setFromArray($formData);
                $this->_specialist->save();
                $this->_helper->redirector('index', 'admin', 'specialists');
            }
        }
        
        $this->view->assign('form', $form);
    }
        
    public function deleteAction() {
        $this->_specialist->delete();
        
        $this->_helper->flashMessenger->setNamespace('messages')
                                      ->addMessage('Элемент удален');
        
        $this->_helper->redirector('index', 'admin', 'specialists');
    }

    
    protected function _specialistNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
							          ->addMessage('Элемент не найден');
        
        $this->_helper->redirector('index', 'admin', 'specialists');
    }
}
