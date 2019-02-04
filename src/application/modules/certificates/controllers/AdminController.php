<?php

class Certificates_AdminController extends Zend_Controller_Action {

    protected $_certificate;
    
    public function init(){
        $this->_helper->layout->setLayout('admin');
        $action = $this->_request->getActionName();

        if (in_array($action, array('edit', 'delete'))) {
            $certificatesTable = new Certificates_Model_DbTable_Certificates();
            $this->_certificate = $certificatesTable->find($this->_getParam('id'))->current();
            if (!$this->_certificate) {
                $this->_certificateNotFound();
            }
        }

        $this->view->assign('action', $action);
    }
        
    public function indexAction() {
        $page = $this->_request->getParam('page', 1);
        $certificatesTable = new Certificates_Model_DbTable_Certificates();

	    $items = $certificatesTable->getAll();

	    $paginator = Zend_Paginator::factory($items);
	    $paginator->setItemCountPerPage(20);
	    $paginator->setCurrentPageNumber($page);

	    $this->view->assign('paginator', $paginator);
    }
    
    public function addAction() {
        $this->_helper->viewRenderer('show-form');
        $certificatesTable = new Certificates_Model_DbTable_Certificates();
        $specialist = $certificatesTable->createRow();
        
        $form = new Certificates_Form_Certificates($specialist);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $imageHelper = new Certificates_Model_Images();
                $formData['image'] = $imageHelper->filter($_FILES['image']['tmp_name'], $_FILES['image']['name']);
                $formData['date'] = date('Y-m-d 00:00:00', strtotime($formData['date']));

                $specialist->setFromArray($formData);
                $specialist->save();
                $this->_helper->redirector('index', 'admin', 'certificates');
            }
        }
        
        $this->view->assign('form', $form);
    }
    
    public function editAction() {
        $this->_helper->viewRenderer('show-form');
        $form = new Certificates_Form_Certificates($this->_certificate);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {

                $imageHelper = new Certificates_Model_Images();
                if (!empty($_FILES['image']['tmp_name'])) {
                    $formData['image'] = $imageHelper->filter($_FILES['image']['tmp_name'], $_FILES['image']['name']);
                } else {
                    $formData['image'] = $this->_certificate->image;
                }
                $formData['date'] = date('Y-m-d 00:00:00', strtotime($formData['date']));

                $this->_certificate->setFromArray($formData);
                $this->_certificate->save();
                $this->_helper->redirector('index', 'admin', 'certificates');
            }
        }
        
        $this->view->assign('form', $form);
    }
        
    public function deleteAction() {
        $this->_certificate->delete();
        
        $this->_helper->flashMessenger->setNamespace('messages')
                                      ->addMessage('Элемент удален');
        
        $this->_helper->redirector('index', 'admin', 'certificates');
    }

    
    protected function _certificateNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
							          ->addMessage('Элемент не найден');
        
        $this->_helper->redirector('index', 'admin', 'certificates');
    }
}
