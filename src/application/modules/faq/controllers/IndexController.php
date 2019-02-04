<?php

class Faq_IndexController extends Zend_Controller_Action {

    protected $_faqModel;
    protected $_auth;

    public function init() {
        $action = $this->_request->getActionName();

        if (in_array($action, array('index'))) {
            $this->_faqModel = new Faq_Model_DbTable_Table;
            $this->_auth = Zend_Auth::getInstance();
        }
    }

    public function indexAction() {
        $questions = $this->_faqModel->getAll(10, (int) $this->_getParam('page', 1));

        $paginator = Zend_Paginator::factory($questions);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(8);
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));

        $this->view->paginator = $paginator;
        $this->view->faqs = $questions;

        if ($this->_auth->hasIdentity()) {
            $this->view->user = true;
        }
    }

    public function addAction() {
        $faqTable = new Faq_Model_DbTable_Table;

        $userModel = new User_Model_DbTable_Users();
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $userInfo = $userModel->find(Zend_Auth::getInstance()->getStorage()->read()->email)->current();
            $form = new Faq_Form_User($userInfo);
        } else {
            $form = new Faq_Form_User();
        }

        $faq = $faqTable->fetchNew();

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $formData['questionDate'] = date('Y-m-d H:i:s');

                $faq->setFromArray($formData);
                $faq->save();

                $mailModel = new Model_Mail();
                try {
                    if ($mailModel->checkOptions()) {
                        $managers = $userModel->getManagersEmails();

                        $mailModel->sendNewQuestionToManagers(
                            $this->view->translate('addReviewSubject'), $managers,
                            $faq['id'],
                            $faq['question'], $formData['fio'], $formData['email']
                        );
                    }
                } catch (Exception $e) {
                    $this->_helper->flashMessenger->setNamespace('errorMessages')
                                                  ->addMessage($this->view->translate('sendToEmailFailure'));;
                }
                
                $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage($this->view->translate('added'));
                $this->_helper->redirector('index', 'index', 'faq');
            }
        }
        $this->view->form = $form;
    }

}
