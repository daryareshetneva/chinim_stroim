<?php

class Admin_IndexController extends Zend_Controller_Action {
    
    public function init() {
        $this->_helper->layout->setLayout('admin');
    }
    
    public function indexAction()
    {
        $redirector = new Zend_Controller_Action_Helper_Redirector;
        $redirector->goToSimple('feedback', 'admin', 'static');
    }
    
    public function tagsAction() {
        $tagsModel = new Model_Tags();
        $tags = $tagsModel->getTags();
                
        $this->view->assign('tags', $tags);
    }
    
    public function editTagAction() {
        $id = $this->_request->getParam('tagId');
        $tagsModel = new Model_DbTable_Resources();
        $res = $tagsModel->getTagsById($id);
        $allAliases = $tagsModel->getTagsAliases();
        $aliases = array();
        for ($i = 0; $i < count($allAliases); $i++) {
            if ($allAliases[$i] != $res['alias']) {
                $aliases[] = $allAliases[$i];
            }
        }
        
        $form = new Admin_Form_Tags($res, $aliases);
        
        $this->view->assign('form', $form);
        $this->view->assign('tag', $res);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $res->setFromArray($formData);
                $res->save();
                $this->_helper->redirector('tags', 'index', 'admin');
            }
        }
    }
    
    public function menuAction() {
        $usersTable = new User_Model_DbTable_Users();
        $feedbackTable =  new Static_Model_DbTable_Feedback();
        $modules = Bootstrap::getModuleList();
        $this->view->assign('users', $usersTable->countUsers());
        $this->view->assign('orders', $feedbackTable->countFeedback());
        $this->view->assign('newOrders', null);
        $this->view->assign('modules', $modules);
    }

    public function settingsAction() {
        $settingsModel = new Model_Settings();
        $settings   = $settingsModel->getSettings();
        $contacts   = $settingsModel->getSettings(['address', 'phone', 'email', 'workTime']);

        $form = new Admin_Form_Settings($settings, $contacts);

        if ($this->_request->isPost()){
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)){
                try{
                    $settingsModel->updateMainSettings($formData);
                    $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage($this->view->translate('settingsEdited'));

                    $this->_helper->redirector('settings', 'index', 'admin');
                } catch (Exception $e) {
                    $this->view->assign('error', $e->getMessage());
                }
            }
        }


        $this->view->assign('form', $form);
    }

    public function analyticsAction(){
        $settingsModel = new Model_Settings();
        $settings   = $settingsModel->getSettings(['yandexMetrika', 'googleAnalytics']);
        $form = new Admin_Form_Analytics($settings);

        if ($this->_request->isPost()){
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)){
                try{
                    $settingsModel->updateMainSettings($formData);
                    $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage($this->view->translate('settingsEdited'));

                    $this->_helper->redirector('analytics', 'index', 'admin');
                } catch (Exception $e) {
                    $this->view->assign('error', $e->getMessage());
                }
            }
        }

        $this->view->assign('form', $form);
    }

    public function socialNetworksAction() {
        $socialNetworksTable = new Admin_Model_DbTable_SocialNetworks();
        $items = $socialNetworksTable->getAll();

        $this->view->assign('items', $items);
    }

    public function mainPhonesAction() {
        $phonesTable = new Admin_Model_DbTable_Phones();
        $items = $phonesTable->getMainPhones();

        $this->view->assign('items', $items);
    }

    public function mainPhonesMobileAction() {
        $phonesTable = new Admin_Model_DbTable_Phones();
        $items = $phonesTable->getMainPhones();

        $this->view->assign('items', $items);
    }

    public function phonesAction() {
        $phonesTable = new Admin_Model_DbTable_Phones();
        $items = $phonesTable->getAll();

        $this->view->assign('items', $items);
    }
   
}