<?php

class Blog_AdminController extends Zend_Controller_Action {

    protected $_diy = null;
    protected $_tags = '';

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $action = $this->_request->getActionName();

        if (in_array($action, array('edit', 'delete'))) {
            $diyTable = new Blog_Model_DbTable_Blog;

            $alias = $this->_request->getParam('alias');
            $this->_diy = $diyTable->getByAlias($alias);
            if (!$this->_diy){
                $this->_diyNotFound();
            }
        }
    }
    
    public function indexAction() {
        $page = $this->_request->getParam('page', 1);
        
        $diyModel = new Blog_Model_Blog;
        $items = $diyModel->getAll();

        $paginator = Zend_Paginator::factory($items);
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);
        $this->view->assign('paginator', $paginator);
    }

    public function addAction()  {
        $diyTable = new Blog_Model_DbTable_Blog;
        $diy = $diyTable->createRow();
        $form = new Blog_Form_Blog($diy, '', $this->existingDate($diy));
        $transliterateModel = new Model_Transliterate();

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                try {
                    if (!empty($_FILES['image']['name'])) {
                        $imageHelper = new Blog_Model_Images();
                        $formData['image'] = $imageHelper->filter($_FILES['image']['tmp_name'], $_FILES['image']['name']);
                    } else {
                        $formData['image'] = '';
                    }

                    if (empty($formData['metaTitle'])) {
                        $formData['metaTitle'] = $transliterateModel->transliterate($formData['title']);
                    }

                    $formData['alias'] = $transliterateModel->transliterate($formData['title']);
                    $formData['date'] = date('Y-m-d', strtotime($formData['date']));
                    $formData['date'] = date('Y-m-d H:i:s');

                    $diy->setFromArray($formData);
                    $diy->save();

                    $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage($this->view->translate('added'));

                    $this->_helper->redirector('index', 'admin', 'blog');
                } catch (Exception $e) {
                    $this->view->assign('error', $e->getMessage());
                    $this->_helper->redirector('add', 'admin', 'blog');
                }
            }
        }
        $this->view->assign('form', $form);
    }

    public function editAction() {
        $transliterateModel = new Model_Transliterate();
        $form = new Blog_Form_Blog($this->_diy, '', $this->existingDate($this->_diy));

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                try {
                    if (!empty($_FILES['image']['name'])) {
                        $imageHelper = new Blog_Model_Images();
                        $formData['image'] = $imageHelper->filter($_FILES['image']['tmp_name'], $_FILES['image']['name']);
                    } else {
                        $formData['image'] = $this->_diy->image;
                    }
                    if (empty($formData['metaTitle'])) {
                        $formData['metaTitle'] = $transliterateModel->transliterate($formData['title']);
                    }

                    $formData['alias'] = $transliterateModel->transliterate($formData['title']);
                    $formData['date'] = date('Y-m-d', strtotime($formData['date']));
                    $dateDb = explode(' ', $this->_diy->date);
                    if ($formData['date'] != $dateDb[0]){
                        $formData['date'] .= date(' H:i:s');
                    } else{
                        $formData['date'] .= ' '.$dateDb[1];
                    }
                    $this->_diy->setFromArray($formData);
                    $this->_diy->save();

                    $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage($this->view->translate('edited'));

                    $this->_helper->redirector('index', 'admin', 'blog');
                } catch (Exception $e) {
                    $this->view->assign('error', $e->getMessage());
                }
            }
        }

        $this->view->assign('form', $form);
    }
    
    public function deleteAction() {       
        $this->_diy->delete();
        $this->_helper->redirector('index', 'admin', 'blog');
    }

    private function existingDate($diy){
        $dateDb = explode(' ', $diy->date);
        $postDate = date('m/d/Y'); // При добавлении
        if ($diy->date) {
            $postDate = date('m/d/Y', strtotime($dateDb[0])); // При редактировании
        }
        return $postDate;
    }
    
    protected function _diyNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
                                      ->addMessage($this->view->translate('diyNotFound'));
        $this->_helper->redirector('index', 'admin', 'blog');
    }
}
