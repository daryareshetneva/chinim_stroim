<?php

class News_AdminController extends Zend_Controller_Action {
    
    protected $_table;
    protected $_article;
    protected $_form;
    
    public function init() {
        $this->_helper->layout->setLayout('admin');
        $action = $this->_request->getActionName();
        $this->_table = new News_Model_DbTable_Table;
        
        if (in_array($action, array('delete', 'edit'))) {
            $this->_article = $this->_table->find($this->_getParam('id'))->current();
            if (!$this->_article)  {
                $this->_articleNotFound();
            }
        }
        $this->view->assign('action', $action);
    }
    
    public function indexAction() {
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Null(count($this->_table->fetchAll())));
        $paginator->setItemCountPerPage(30);
        $paginator->setPageRange(8);
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $this->view->news = $this->_table->getPages($this->_getParam('page', 1), 30);
        $this->view->paginator = $paginator;
    }
    
    public function addAction() {
        $this->_helper->viewRenderer('show-form');
        $newArticle = $this->_table->createRow();

        $form = new News_Form_News($newArticle);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $transliterateModel = new Model_Transliterate();
                $formData['alias'] = $transliterateModel->transliterate($formData['title']);
                $formData['date'] = date('Y-m-d 00:00:00', strtotime($formData['date']));

                if (!empty($_FILES['image']['tmp_name'])) {
                    $imageHelper = new News_Model_Images();
                    $formData['image'] = $imageHelper->filter($_FILES['image']['tmp_name'], $_FILES['image']['name']);
                }

                $newArticle->setFromArray($formData);
                $newArticle->save();

                $this->_helper->flashMessenger->setNamespace('messages')
                                              ->addMessage($this->view->translate('added'));

                $this->_helper->redirector('index', 'admin', 'news');
            }
        }
        $this->view->assign('form', $form);
    }
    
    public function deleteAction() {
        $this->_article->delete();
        
        $this->_helper->flashMessenger->setNamespace('messages')
                                       ->addMessage($this->view->translate('deleted'));
        
        $this->_helper->redirector('index', 'admin', 'news');
    }
    
    public function editAction() {
        $this->_helper->viewRenderer('show-form');
        $form = new News_Form_News($this->_article);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $transliterateModel = new Model_Transliterate();
                $formData['alias'] = $transliterateModel->transliterate($formData['title']);
                $formData['date'] = date('Y-m-d 00:00:00', strtotime($formData['date']));

                if (!empty($_FILES['image']['tmp_name'])) {
                    $imageHelper = new News_Model_Images();
                    $formData['image'] = $imageHelper->filter($_FILES['image']['tmp_name'], $_FILES['image']['name']);
                } else {
                    $formData['image'] = $this->_article->image;
                }

                $this->_article->setFromArray($formData);
                $this->_article->save();

                $this->_helper->flashMessenger->setNamespace('messages')
                                              ->addMessage($this->view->translate('edited'));
                
                $this->_helper->redirector('index', 'admin', 'news');
            }
        }
        $this->view->assign('form', $form);
    }
    
    protected function _articleNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
                                      ->addMessage($this->view->translate('notFound'));
        
        $this->_helper->redirector('index', 'admin', 'news');
    }
    
}
