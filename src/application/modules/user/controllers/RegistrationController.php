<?php

class User_RegistrationController extends ItRocks_Controller_Action {

    public function indexAction() {
        $mailModel = new Model_Mail();
        $usersTable = new User_Model_DbTable_Users();

        $form = new User_Form_Registration();

        if ($this->_request->getPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                try {
                    $user = $usersTable->createRow();
                    $formData['password'] = md5($formData['password']);
                    $formData['type'] = db_Users::TYPE_USER;
                    $formData['discount'] = 0;
                    $formData['registrationDate'] = date('Y-m-d H:i:s');
                    $user->setFromArray($formData);
                    $user->save();

                    try {
                        if ($mailModel->checkOptions()) {
                            $mailModel->sendBeforeRegistration(
                                    $formData['email'], $formData['password'],
                                    $formData['fio'], $formData['phone']);
                        }
                    } catch (Exception $e) {
                        $this->_helper->flashMessenger->setNamespace('errorMessages')
                                ->addMessage($this->view->translate('sendToEmailFailure'));;
                    }

                    $this->_helper->flashMessenger->setNamespace('blueMessages')
                            ->addMessage($this->view->translate('registrationSuccess'));
                    
                    $this->_helper->redirector('register-success', 'registration', 'user');
                } catch (Exception $e) {
                    $this->_helper->flashMessenger->setNamespace('errorMessages')
                            ->addMessage($this->view->translate('registrationFailure'))
                            ->addMessage($e->getMessage());
                }
            }
        }
        $this->view->assign('form', $form);
        $this->addTagsAuto();
    }

    public function forgetAction() {
        $mailModel = new Model_Mail();

        $form = new User_Form_Forget();

        if ($this->_request->getPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                try {
                    $usersTable = new User_Model_DbTable_Users();
                    $user = $usersTable->getByEmail($formData['email']);
                    if (!$user) {
                        $form->email->addError('emailNotExist');
                    } else {
                        $newPassword = $this->_randomPassword();
                        $user->password = md5($newPassword);
                        $user->save();
                        $mailModel->sendNewPassword($user->email, $newPassword);
                        $this->_helper->redirector('forget-success', 'registration', 'user');
                    }
                } catch (Exception $e) {
                    $this->_helper->flashMessenger->setNamespace('errorMessages')
                            ->addMessage($this->view->translate('forgetError'));
                    $this->_helper->redirector('forget', 'registration', 'user');
                }
            }
        }
        $this->addTagsAuto();
        $this->view->assign('form', $form);
    }
    
    public function registerSuccessAction() {
        
    }

    public function forgetSuccessAction() {

    }

    private function _randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

}
