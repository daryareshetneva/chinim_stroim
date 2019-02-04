<?php

class Admin_PhonesController extends Zend_Controller_Action {

    protected $_page = 1;
    protected $_item;
    
    public function init() {
        $this->_helper->layout->setLayout('admin');

        $action = $this->_request->getActionName();
        $this->_page = $this->_request->getParam('page', 1);

        if (in_array($action, ['edit', 'delete'])) {
            $phonesTable = new Admin_Model_DbTable_Phones();
            $this->_item = $phonesTable->find($this->_request->getParam('id'))->current();

            if (!$this->_item) {
                $this->_itemNotFound();
            }
        }

        $this->view->assign('page', $this->_page);
    }
    
    public function indexAction() {
        $phonesTable = new Admin_Model_DbTable_Phones();
        $items = $phonesTable->getAll();

        $paginator = Zend_Paginator::factory($items);
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($this->_page);

        $this->view->assign('paginator', $paginator);

        $this->view->headTitle()->set('Телефоны');
    }

    public function addAction() {
        $this->_helper->viewRenderer('show-form');

        $phonesTable = new Admin_Model_DbTable_Phones();
        $item = $phonesTable->createRow();

        $form = new Admin_Form_Phone($item);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {

                $item->setFromArray($formData);
                $item->save();

                $this->_helper->flashMessenger->setNamespace('messages')
                    ->addMessage($this->view->translate('added'));

                $this->_helper->redirector('index', 'phones', 'admin');
            }
        }

        $this->view->assign('form', $form);

        $this->view->headTitle()->set('Добавление телефона');
    }

    public function editAction() {
        $this->_helper->viewRenderer('show-form');

        $form = new Admin_Form_Phone($this->_item);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $this->_item->setFromArray($formData);
                $this->_item->save();

                $this->_helper->flashMessenger->setNamespace('messages')
                    ->addMessage($this->view->translate('edited'));

                $this->_helper->redirector('index', 'phones', 'admin', ['page' => $this->_page]);
            }
        }

        $this->view->assign('form', $form);

        $this->view->headTitle()->set('Редактирование телефона');
    }

    public function deleteAction() {
        $this->_item->delete();

        $this->_helper->flashMessenger->setNamespace('messages')
            ->addMessage($this->view->translate('deleted'));

        $this->_helper->redirector('index', 'phones', 'admin', ['page' => $this->_page]);
    }

    protected function _itemNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
            ->addMessage($this->view->translate('notFound'));

        $this->_helper->redirector('index', 'phones', 'admin', ['page' => $this->_page]);
    }
}