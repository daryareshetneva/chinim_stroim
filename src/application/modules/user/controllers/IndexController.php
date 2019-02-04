<?php

class User_IndexController extends ItRocks_Controller_Action {

	protected $_auth;
    protected $_userTable = null;

    // объект пользователя
    protected $_user = null;

    // массив данных пользователя
    protected $_userData;

	public function init() {
		$this->_auth = Zend_Auth::getInstance();
        $this->_user = $this->_user = $this->_auth->getIdentity();

        if ($this->_user) {
            $this->_userTable = new User_Model_DbTable_Users();
            $this->_userData = $this->_userTable->getUserByEmailForOrder($this->_user->email);
        }
	}

	public function indexAction() {
		$email = $this->_auth->getStorage()->read()->email;
		$userModel = new User_Model_DbTable_Users();
		$user = $userModel->getByEmail( $email );

        if (!$user) {
            $this->_helper->redirector( 'index', 'index', 'default' );
        }
        
        $userAddressTable = new User_Model_DbTable_UserAddresses;
        $addresses = $userAddressTable->getByUserId($user['id']);

		$this->view->assign('userData', $user );
        $this->view->assign('addresses', $addresses);
	}

	public function ordersAction() {
        $status = [
            0 => 'В обработке',
            1 => 'Выполнен',
            2 => 'Отменен'
        ];

        $ordersTable = new Shop_Model_Orders();
        $orders = $ordersTable->getOrdersOfUser($this->_userData['id']);
        $page = $this->_request->getParam('page', 1);

        $paginator = Zend_Paginator::factory($orders);
        $paginator->setItemCountPerPage(5);
        $paginator->setCurrentPageNumber($page);

        $this->view->assign('status', $status);
        $this->view->assign('paginator', $paginator);
    }

    public function viewOrderAction(){
        $orderId = $this->_request->getParam('id');

        $orderTable = new Shop_Model_OrderedProducts();
        $order = $orderTable->getOrderByIdOfUser($orderId,$this->_userData['id']);
        $this->view->assign('orders', $order);
    }

    public function cabinetMenuAction(){
        $this->view->assign('user', $this->_userData);
    }

    public function editProfileAction(){

        $userModel = new User_Model_User();

        $form = new User_Form_EditProfile($this->_userData);

        if ( $this->_request->isPost() ) {
            $formData = $this->_request->getPost();
            if ( $form->isValid( $formData ) ) {
                if ($formData['password'] !== ''){
                    $formData['password'] = md5($formData['password']);
                } else {
                    unset( $formData['password'] );
                }
                unset( $formData['currentPassword'] );
                unset( $formData['email'] );
                unset( $formData['confirm'] );
                $userModel->updateUser($this->_userData['id'], $formData);
            } else {
                $form->populate( $formData );
            }
        }

        $this->view->assign('form', $form);
    }

	public function editAction() {


		$email = $this->_auth->getStorage()->read()->email;
		$userModel = new User_Model_Users();
		$user = $userModel->getUserByEmail( $email );

		$form = new User_Form_UserEdit( $user );

		if ( $this->_request->isPost() ) {

			$formData = $this->_request->getPost();

			if ( $form->isValid( $formData ) ) {

				$formData['password'] = md5( $formData['password'] );

				unset( $formData['confirm'] );
				$userModel->updateUser( $formData, $user['id'] );

				$this->flashMessage( 'blueMessages', 'profileEdited', true );

				$this->_helper->redirector( 'index', 'index', 'user' );
			} else {
				$form->populate( $formData );
			}
		}

		$this->view->form = $form;
	}

	public function panelAction() {
		$auth = Zend_Auth::getInstance();
		$identity = $auth->hasIdentity();
		if ( $identity ) {
			$userModel = new User_Model_Users();
			$user = $userModel->getUserByEmail( $auth->getStorage()->read()->email );
			$this->view->assign( 'user', $user );

			$orderModel = new Shop_Model_Orders();
			$notPayedOrder = $orderModel->getOrderNotPayedByUserId( $user['id'] );
			$this->view->assign( 'notPayedOrder', $notPayedOrder );
		}
	}

    public function addAddressAction() {
        $userAddressTable = new User_Model_DbTable_UserAddresses;
        $address = $userAddressTable->fetchNew();
        $form = new User_Form_Address($address);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                try {
                    $email = $this->_auth->getStorage()->read()->email;
                    $userModel = new User_Model_Users();
                    $user = $userModel->getUserByEmail($email);
                    if (empty($user)) 
                        throw new Exception('CantFindUser');

                    $formData['userId'] = $user['id'];                    
                    $address->setFromArray($formData);
                    $address->save();
                    $this->_helper->redirector( 'index', 'index', 'user' );
                } catch (Exception $e) {
                    $this->_helper->redirector('add-address', 'index', 'user' );
                }
            }
        }

        $this->view->assign('form', $form);
    }

    public function editAddressAction() {
        $userAddressTable = new User_Model_DbTable_UserAddresses;
        $address = $userAddressTable->find($this->_request->getParam('id'))->current();
        if (!$address)
            $this->_addressNotFound();
        $form = new User_Form_Address($address);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                try {
                    $email = $this->_auth->getStorage()->read()->email;
                    $userModel = new User_Model_Users();
                    $user = $userModel->getUserByEmail($email);
                    if (empty($user)) 
                        throw new Exception('CantFindUser');

                    $formData['userId'] = $user['id'];                    
                    $address->setFromArray($formData);
                    $address->save();
                    $this->_helper->redirector( 'index', 'index', 'user' );
                } catch (Exception $e) {
                    $this->_helper->redirector('add-address', 'index', 'user' );
                }
            }
        }

        $this->view->assign('form', $form);
    }

    public function removeAddressAction() {
        $userAddressTable = new User_Model_DbTable_UserAddresses;
        $address = $userAddressTable->find($this->_request->getParam('id'))->current();
        if (!$address)
            $this->_addressNotFound();
        $address->delete();
        $this->_helper->redirector( 'index', 'index', 'user' );
    }

	public function loginAction() {
        if ( $this->_auth->hasIdentity() ) {

            $userModel = new User_Model_DbTable_Users();
            $userInfo = $userModel->find( $this->_auth->getStorage()->read()->id )->current();
            if (($userInfo['type'] == 'administrator') || ($userInfo['type'] == 'manager')){
                $this->_helper->redirector('index', 'index', 'admin');
            } else {
                $this->_goToHome();
            }
        }

        $baseUrl = "http://" . $_SERVER['SERVER_NAME'];
        $socialConfig = $this->getInvokeArg('bootstrap')->getOption('social');
        $socialAuthModel = new User_Model_SocialAuth();

		$loginForm = new User_Form_Login;

		if ( $this->_request->isPost() ) {
			if ( $loginForm->isValid( $this->_request->getPost() ) ) {

				$values = $loginForm->getValues();
				$adapter = Zend_Db_Table::getDefaultAdapter();
				$authAdapter = new Zend_Auth_Adapter_DbTable( $adapter, 'Users', 'email', 'password' );
				$authAdapter->setIdentity( $values['email'] );
				$authAdapter->setCredential( md5( $values['password'] ) );
				$result = $this->_auth->authenticate( $authAdapter );


				if ( $result->isValid() ) {
					$data = $authAdapter->getResultRowObject();
					$data->password = '';

					$userTable = new User_Model_DbTable_Users;
                    $user = $userTable->getByEmail($data->email);
					$user->lastvisitDate = date('Y-m-d H:i:s');
					$user->save();

					$this->_auth->getStorage()->write($data);
                    $this->_helper->redirector('index', 'index', 'admin');

				} else {
					$loginForm->email->addError($this->view->translate('wrongEmail' ));
					$loginForm->password->addError($this->view->translate('wrongPassword'));
				}
			}
		}

		$this->view->assign('loginForm', $loginForm);
        $this->view->assign('vkUrl', $socialAuthModel->getAuthUrl('vk', $socialConfig['vk']['clientId'], $socialConfig['vk']['secret'], $baseUrl . $this->view->url(['module' => 'user', 'controller' => 'index', 'action' => 'vk-auth'], 'default', true)));
        $this->view->assign('fbUrl', $socialAuthModel->getAuthUrl('fb', $socialConfig['fb']['clientId'], $socialConfig['fb']['secret'], $baseUrl .  $this->view->url(['module' => 'user', 'controller' => 'index', 'action' => 'facebook-auth'], 'default', true)));
        $this->view->assign('googleUrl', $socialAuthModel->getAuthUrl('google', $socialConfig['google']['clientId'], $socialConfig['google']['secret'], $baseUrl . $this->view->url(['module' => 'user', 'controller' => 'index', 'action' => 'google-auth'], 'default', true)));
        $this->view->assign('yandexUrl', $socialAuthModel->getAuthUrl('yandex', $socialConfig['yandex']['clientId'], $socialConfig['yandex']['secret'], $baseUrl . $this->view->url(['module' => 'user', 'controller' => 'index', 'action' => 'yandex-auth'], 'default', true)));
	}

    public function vkAuthAction() {
        $baseUrl = "http://" . $_SERVER['SERVER_NAME'];
        $code = $this->_request->getParam('code', null);
        if (!$code) {
            $this->_helper->redirector('user', 'index', 'login');
        } else {
            $socialConfig = $this->getInvokeArg('bootstrap')->getOption('social');
            $curlRequest = new Model_Request_Curl();
            $vkAuth = new User_Model_SocialAuth_Vk($socialConfig['vk']['secret'], $socialConfig['vk']['clientId'], $baseUrl . $this->view->url(['module' => 'user', 'controller' => 'index', 'action' => 'vk-auth'], 'default', true), $curlRequest);
            $token = $vkAuth->getToken($code);
            $userInfo = $vkAuth->getUserInfo();
            $user = [
                'mail' => $token['email'],
                'fio' => $userInfo['response'][0]['last_name'] . ' ' . $userInfo['response'][0]['first_name'],
                'uid' => $userInfo['response'][0]['uid'],
                'provider' => 'vk'
            ];

            $this->_authWithSocial($user['mail'], 'vk', $user['uid'], $user['fio']);

            $this->_helper->redirector('index', 'index', 'default');
        }
    }

    public function yandexAuthAction(){
        $baseUrl = "http://" . $_SERVER['SERVER_NAME'];
        $code = $this->_request->getParam('code', null);
        if (!$code) {
            $this->_helper->redirector('user', 'index', 'login');
        } else {
            $socialConfig = $this->getInvokeArg('bootstrap')->getOption('social');
            $curlRequest = new Model_Request_Curl();
            $yandexAuth = new User_Model_SocialAuth_Yandex($socialConfig['yandex']['secret'], $socialConfig['yandex']['clientId'], '', $curlRequest);
            $token = $yandexAuth->getToken($code);
            $userInfo = $yandexAuth->getUserInfo();

            $user = [
                'mail' => $userInfo['default_email'],
                'fio' => $userInfo['last_name'] . ' ' . $userInfo['first_name'],
                'uid' => $userInfo['id'],
                'provider' => 'yandex'
            ];

            $this->_authWithSocial($user['mail'], $user['provider'], $user['uid'], $user['fio']);

            $this->_helper->redirector('index', 'index', 'default');
        }
    }

    public function googleAuthAction() {
        $baseUrl = "http://" . $_SERVER['SERVER_NAME'];
        $code = $this->_request->getParam('code', null);
        if (!$code) {
            $this->_helper->redirector('user', 'index', 'login');
        } else {
            $socialConfig = $this->getInvokeArg('bootstrap')->getOption('social');
            $curlRequest = new Model_Request_Curl();
            try {
                $googleAuth = new User_Model_SocialAuth_Google($socialConfig['google']['secret'], $socialConfig['google']['clientId'], $baseUrl . $this->view->url(['module' => 'user', 'controller' => 'index', 'action' => 'google-auth'], 'default', true), $curlRequest);
                $token = $googleAuth->getToken($code);
                $userInfo = $googleAuth->getUserInfo();

                $user = [
                    'mail' => $userInfo['email'],
                    'fio' => $userInfo['name'],
                    'uid' => $userInfo['id'],
                    'provider' => 'google'
                ];

                $this->_authWithSocial($user['mail'], $user['provider'], $user['uid'], $user['fio']);
            } catch (Exception $e) {

            }

            $this->_helper->redirector('index', 'index', 'default');
        }
    }

    public function facebookAuthAction() {
        $baseUrl = "http://" . $_SERVER['SERVER_NAME'];
        $code = $this->_request->getParam('code', null);
        if (!$code) {
            $this->_helper->redirector('user', 'index', 'login');
        } else {
            $socialConfig = $this->getInvokeArg('bootstrap')->getOption('social');
            $curlRequest = new Model_Request_Curl();
            try {
                $fbAuth = new User_Model_SocialAuth_Facebook($socialConfig['fb']['secret'], $socialConfig['fb']['clientId'], $baseUrl . $this->view->url(['module' => 'user', 'controller' => 'index', 'action' => 'facebook-auth'], 'default', true), $curlRequest);
                $token = $fbAuth->getToken($code);
                $userInfo = $fbAuth->getUserInfo();

                $user = [
                    'mail' => $userInfo['email'],
                    'fio' => $userInfo['last_name'] . ' ' . $userInfo['first_name'],
                    'uid' => $userInfo['id'],
                    'provider' => 'fb'
                ];

                $this->_authWithSocial($user['mail'], $user['provider'], $user['uid'], $user['fio']);
            } catch (Exception $e) {

            }

            $this->_helper->redirector('index', 'index', 'default');
        }
    }


    public function logoutAction() {
		$this->_auth->clearIdentity();
		$this->_goToHome();
	}

	protected function _goToCabinet() {
		$this->_helper->redirector( 'index', 'index', 'user' );
	}
    
    protected function getHostUrl() {
        return "http://" . $this->getRequest()->getHttpHost() . $this->getFrontController()->getBaseUrl();
    }

    private function _authWithSocial($userEmail, $socialProvider, $userUid, $userFio) {
        $usersTable = new User_Model_DbTable_Users();
        $existUser = $usersTable->find($userEmail)->current();
        if (!$existUser) {
            $auth = $usersTable->socialAuth($socialProvider, $userUid);
            if (!$auth) {
                $usersTable->addUserSocial($userFio, $userEmail, $socialProvider, $userUid);
                $existUser = $usersTable->find($userEmail)->current();
            } else {
                $usersTable->updateUserMailById($userEmail, $auth['id']);
                $existUser = $usersTable->find($userEmail)->current();
            }

        } else {
            if ($socialProvider != $existUser->provider || $userUid != $existUser->uid) {
                $existUser->uid = $userUid;
                $existUser->provider = $socialProvider;
                $existUser->save();
            }
        }

        $adapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($adapter,
            'Users', 'mail', 'password');
        $authAdapter->setIdentity($existUser->mail);
        $authAdapter->setCredential($existUser->password);
        $result = $this->_auth->authenticate($authAdapter);

        if ($result->isValid()) {
            $data = $authAdapter->getResultRowObject();
            $data->status = $usersTable->getStatus($existUser->mail);
            $this->_auth->getStorage()->write($data);

            // Update last visit date
            $usersTable->updateUserLastVisitDate($data->id, date('Y-m-d H:i:s'));
        }
    }

}
