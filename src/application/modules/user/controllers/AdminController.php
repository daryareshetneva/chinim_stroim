<?php

class User_AdminController extends ItRocks_Controller_Action {

    protected $_auth;
    protected $_user;
    protected $_page = 1;

    public function init() {
        $this->_helper->layout->setLayout('admin');

        $this->_page = $this->_request->getParam('page', 1);
        $action = $this->_request->getActionName();

        if (in_array($action, ['edit-user', 'remove-user'])) {
            $userId = $this->_request->getParam('id');
            $usersTable = new User_Model_DbTable_Users();
            $this->_user = $usersTable->find($userId)->current();
            if (!$this->_user) {
                $this->_userNotFound();
            }
        }

        $this->view->assign('page', $this->_page);
    }

    public function indexAction() {
        $usersTable = new User_Model_DbTable_Users();
        $query = $this->_getParam('query', '');

        if ($query) {
            $users = $usersTable->search($query);
        } else {
            $users = $usersTable->getAll();
        }

        $form = new User_Form_Search($query);

        $paginator = Zend_Paginator::factory($users);
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($this->_page);

        $this->view->assign('paginator', $paginator);
        $this->view->assign('query', $query);
        $this->view->assign('searchForm', $form);
    }

    public function addUserAction()
    {
        $form = new User_Form_Add();

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $formData['password'] = md5($formData['password']);
                $formData['registrationDate'] = date('Y-m-d H:i:s');
                $formData['lastvisitDate'] = '1970-01-01 00:00:00';

                $usersTable = new User_Model_User();

                if (!$usersTable->addUser($formData)) {
                    $this->view->assign('error', $form->getTranslator()->_('emailAlreadyExists'));
                } else {
                    $this->_helper->redirector('index', 'admin', 'user');
                }
            }
        }

        $this->view->assign('form', $form);
    }

    public function editUserAction() {
        $page = $this->_getParam('page');
        $form = new User_Form_Edit($this->_user);

        if ($this->_request->isPost()) {
            $formData =$this->_request->getPost();
            if ($form->isValid($formData)) {
                if (!empty($formData['password'])) {
                    $formData['password'] = md5($formData['password']);
                } else {
                    unset($formData['password']);
                }

                $this->_user->setFromArray($formData);
                $this->_user->save();
                $this->_helper->redirector('index', 'admin', 'user');

            }
        }

        $this->view->assign('form', $form);
    }


    public function removeUserAction() {
        try {
            if (1 == $this->_user->id) {
                throw new Exception('canNotDeleteAdmin');
            }
            $this->_user->delete();
            $this->flashMessage('messages', 'successRemovedUser');
        } catch (Exception $e) {
            $this->flashMessage('errorMessages', 'failureRemovedUser');
        }
        $this->_helper->redirector('index', 'admin', 'user', array('page' => $this->_page));
    }

    protected function _userNotFound() {
        $this->_helper->redirector('index', 'admin', 'user');
    }

}
