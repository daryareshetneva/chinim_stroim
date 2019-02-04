<?php

class HotelRooms_AdminController extends Zend_Controller_Action{

    protected $_category;
    protected $_page = 1;
    
    public function init(){
        $this->_helper->layout->setLayout('admin');
        $action = $this->_request->getActionName();
        $this->_page = $this->_request->getParam('page', 1);
 
        if (in_array($action, array('edit', 'delete'))) {
            $categoriesTable = new HotelRooms_Model_DbTable_Categories();
            $this->_category = $categoriesTable->find($this->_getParam('id'))->current();
            if (!$this->_category) $this->_categoryNotFound();
        }  

        $this->view->assign('action', $action);
        $this->view->assign('page', $this->_page);
    }
        
    public function indexAction() {

        $categoriesTable = new HotelRooms_Model_DbTable_Categories();

	    $categories = $categoriesTable->getAll();

	    $paginator = Zend_Paginator::factory($categories);
	    $paginator->setItemCountPerPage(20);
	    $paginator->setCurrentPageNumber($this->_page);

	    $this->view->assign('paginator', $paginator);
    }
    
    public function addAction() {
        $this->_helper->viewRenderer('show-form');

        $categoriesTable = new HotelRooms_Model_DbTable_Categories();
        $category = $categoriesTable->createRow();
        
        $form = new HotelRooms_Form_Category($category);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $transliterateModel = new Model_Transliterate();
                $formData['alias'] = $transliterateModel->transliterate($formData['title']);
                $formData['position'] = $categoriesTable->getMaxPosition() + 1;

                $category->setFromArray($formData);
                $category->save();

                $this->_helper->redirector('index', 'admin', 'hotelrooms');
            }
        }
        
        $this->view->assign('form', $form);
    }
    
    public function editAction() {
        $this->_helper->viewRenderer('show-form');

        $form = new HotelRooms_Form_Category($this->_category);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $transliterateModel = new Model_Transliterate();
                $formData['alias'] = $transliterateModel->transliterate($formData['title']);

                $this->_category->setFromArray($formData);
                $this->_category->save();

                $this->_helper->redirector('index', 'admin', 'hotelrooms', ['page' => $this->_page]);
            }
        }
        
        $this->view->assign('form', $form);
    }
        
    public function deleteAction() {
        $this->_category->delete();
        
        $this->_helper->flashMessenger->setNamespace('messages')
                                      ->addMessage('Категория удалена');

        $this->_helper->redirector('index', 'admin', 'hotelrooms', ['page' => $this->_page]);
    }
    
    public function replyAction(){
        
    }
    
    protected function _categoryNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
							          ->addMessage('Запись не найдена');
        
        $this->_helper->redirector('index', 'admin', 'hotelrooms');
    }
}
