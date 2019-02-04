<?php

class Faq_AdminController extends Zend_Controller_Action{
    
    protected $_faqModel;
    protected $_faq;
    
    public function init(){
        $this->_helper->layout->setLayout('admin');
        $action = $this->_request->getActionName();
        
        if (in_array($action, array('index', 'reply', 'delete'))) {
            $this->_faqModel = new Faq_Model_DbTable_Table();
        }
 
        if (in_array($action, array('reply', 'delete'))) { 
            $this->_faq = $this->_faqModel->find($this->_getParam('id'))->current();
            if (!$this->_faq) $this->_faqsNotFound();
        }  
      
    }
    
    public function indexAction(){
        $questions = $this->_faqModel->getAdminAll();
        
        $paginator = Zend_Paginator::factory($questions);
        $paginator->setItemCountPerPage(30);
        $paginator->setPageRange(8);
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));

        $this->view->paginator = $paginator;
        $this->view->faqs = $questions;
    }
    
    public function deleteAction(){
        $this->_faq->delete();
        
        $this->_helper->flashMessenger->setNamespace('messages')
                                      ->addMessage($this->view->translate('deleted'));
        
        $this->_helper->redirector('index', 'admin', 'faq');
    }
    
    public function replyAction(){
        $messages = '<ul>';
        $form = new Faq_Form_Admin($this->_faq);
        
        $this->view->faq = $this->_faq;
        $this->view->assign('form', $form);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $formData['answerDate'] = date('Y-m-d H:i:s');
                if (isset($formData['show'])) {
                    $formData['show'] = 1;
                } else {
                    $formData['show'] = 0;
                }
                $this->_faq->setFromArray($formData);
                $this->_faq->save();
                if ($formData['reply'] == 1) {
                    $mailModel = new Mail_Model_Mail;
                    try {
                        $mailModel->sendReviewReply(
                                $this->view->translate('faqEmailTitle'),
                                $this->_faq->faq, $formData['answer'],
                                $this->_faq->email, $this->_faq->fio);
                        $messages .= '<li>' . $this->view->translate('faqEmailSent') . '</li>';
                    } catch (Exception $e) {
                        $this->_helper->flashMessenger->setNamespace('errorMessages')
							          ->addMessage($this->view->translate('faqEmailNotSent'));
                    }
                }
                $messages .= '<li>' . $this->view->translate('replied') . '</li>';
                $messages .= '</ul>';
                $this->_helper->flashMessenger->setNamespace('messages')
                                              ->addMessage($messages);
                $this->_helper->redirector('index', 'admin', 'faq');
            }
        }
        
    }
    
    protected function _faqsNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
							          ->addMessage($this->view->translate('notFound'));
        
        $this->_helper->redirector('index', 'admin', 'faq');
    }
}