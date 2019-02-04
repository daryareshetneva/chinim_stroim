<?php

class Shop_AdminImportController extends ItRocks_Controller_Action {

    public function init() {
        $this->_helper->layout->setLayout('admin');
    }

    public function indexAction()
    {
        $form = new Shop_Form_Import();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                try {
                    $importModel = new Shop_Model_Import();
                    $importModel->setFile($_FILES['file']['tmp_name']);
                    $importModel->setFilename($_FILES['file']['name']);
                    $importModel->import();

                    $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage('Импорт товаров успешно завершен');

                } catch (Exception $e) {
                    $this->_helper->flashMessenger->setNamespace('errorMessages')
                        ->addMessage($e->getMessage());
                }
                $this->_helper->redirector('index', 'admin-import', 'shop');
            }
        }

        $this->view->assign('form', $form);
        $this->_addMeta('Импорт товаров', '', '');
    }


}