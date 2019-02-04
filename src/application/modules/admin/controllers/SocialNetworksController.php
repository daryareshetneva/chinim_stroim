<?php

class Admin_SocialNetworksController extends Zend_Controller_Action {

    protected $_page = 1;
    protected $_item;
    
    public function init() {
        $this->_helper->layout->setLayout('admin');

        $action = $this->_request->getActionName();
        $this->_page = $this->_request->getParam('page', 1);

        if (in_array($action, ['edit', 'delete'])) {
            $socialNetTable = new Admin_Model_DbTable_SocialNetworks();
            $this->_item = $socialNetTable->find($this->_request->getParam('id'))->current();

            if (!$this->_item) {
                $this->_itemNotFound();
            }
        }

        $this->view->assign('page', $this->_page);
    }
    
    public function indexAction() {
        $socialNetTable = new Admin_Model_DbTable_SocialNetworks();
        $items = $socialNetTable->fetchAll();

        $paginator = Zend_Paginator::factory($items);
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($this->_page);

        $this->view->assign('paginator', $paginator);

        $this->view->headTitle()->set('Соц.сети');
    }

    public function addAction() {
        $this->_helper->viewRenderer('show-form');

        $socialNetTable = new Admin_Model_DbTable_SocialNetworks();
        $item = $socialNetTable->createRow();

        $form = new Admin_Form_SocialNetwork($item);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {

                $imageHelper = new Model_Images_Common();
                $formData['img'] = $imageHelper->filter($_FILES['img']['tmp_name'], $_FILES['img']['name']);


                $item->setFromArray($formData);
                $item->save();

                $this->_helper->flashMessenger->setNamespace('messages')
                    ->addMessage($this->view->translate('added'));

                $this->_helper->redirector('index', 'social-networks', 'admin');
            }
        }

        $this->view->assign('form', $form);

        $this->view->headTitle()->set('Добавление Соц.сети');
    }

    public function editAction() {
        $this->_helper->viewRenderer('show-form');

        $form = new Admin_Form_SocialNetwork($this->_item);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {

                if (!empty($_FILES['img']['tmp_name'])) {
                    $imageHelper = new Model_Images_Common();
                    $formData['img'] = $imageHelper->filter($_FILES['img']['tmp_name'], $_FILES['img']['name']);
                } else{
                    $formData['img'] = $this->_item->img;
                }

                $this->_item->setFromArray($formData);
                $this->_item->save();

                $this->_helper->flashMessenger->setNamespace('messages')
                    ->addMessage($this->view->translate('edited'));

                $this->_helper->redirector('index', 'social-networks', 'admin', ['page' => $this->_page]);
            }
        }

        $this->view->assign('form', $form);

        $this->view->headTitle()->set('Редактирование Соц.сети');
    }

    public function deleteAction() {
        $this->_item->delete();

        $this->_helper->flashMessenger->setNamespace('messages')
            ->addMessage($this->view->translate('deleted'));

        $this->_helper->redirector('index', 'social-networks', 'admin', ['page' => $this->_page]);
    }

    protected function _itemNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
            ->addMessage($this->view->translate('notFound'));

        $this->_helper->redirector('index', 'social-networks', 'admin', ['page' => $this->_page]);
    }
}