<?php

class Admin_MailController extends ItRocks_Controller_Action {

    protected $_model;
    protected $_settingsTable;
    protected $_settings;

    public function __construct(
            \Zend_Controller_Request_Abstract $request,
            \Zend_Controller_Response_Abstract $response,
            array $invokeArgs = array()
    ) {
        parent::__construct($request, $response, $invokeArgs);

        $this->_helper->layout->setLayout('admin');

        $this->_model = new Model_Mail();
        $this->_settingsTable = new Model_DbTable_Settings();
        $this->_settings = $this->_settingsTable->getEmailSettings();
    }

    /*     * **********************************
     * 	    INTERFACE
     * ********************************** */

    public function settingsAction() {
        $form = new Admin_Form_MailSettings($this->_settings);
        if ($this->getRequest()->isPost()) {
            $this->editSettings($form);
        }
        $this->view->assign('form', $form);
    }

    public function checkEmailConnectionAction() {
        $this->checkConnection();
    }

    public function sendAction() {
        $form = new Admin_Form_Mail();
        if ($this->getRequest()->isPost()) {
            $this->send($form);
        }
        $this->view->assign('form', $form);
    }

    /*     * **********************************
     * 	    IMPLEMENTS
     * ********************************** */

    protected function editSettings($form) {
        $formData = $this->getRequest()->getPost();
        if ($form->isValid($formData)) {
            $this->_settingsTable->saveEmailSettings($formData);
            $this->flashMessage('messages', 'emailSettingsSuccessfullyEdit');
            $this->_helper->redirector('settings', 'mail', 'admin');
        }
    }

    protected function checkConnection() {
        $this->_helper->viewRenderer->setNoRender(true);
        
        try {
            $this->_model->sendTestEmail();
            $this->flashMessage('messages', 'emailSentSuccess');
        } catch (Exception $e) {
            $this->flashMessage('errorMessages', $e->getMessage());
        }

        $this->_helper->redirector('settings', 'mail', 'admin');
    }

    protected function send($form) {
        $formData = $this->_request->getPost();

        if ($form->isValid($formData)) {
            $users = $this->getUsers();
            $this->_model->sendDistribution($formData['subject'],
                    $formData['title'], $formData['body'], $users);

            $this->flashMessage('messages', 'distributionStarted');

            $this->_helper->redirector('index', 'index', 'admin');
        }
    }

    protected function getUsers() {
        $usersTable = new User_Model_DbTable_Users();
        return $usersTable->getUsers();
    }

}
