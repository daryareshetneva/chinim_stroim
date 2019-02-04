<?php

class Plugin_Auth extends Zend_Controller_Plugin_Abstract {
	
    protected $_auth = null;
    protected $_acl = null;
    protected $_resources;

    protected $_noAuth = array (
        'module' => 'default',
        'controller' => 'index',
        'action' => 'index'
    );
        
    protected $_noAcl = array(
        'module' => 'default',
        'controller' => 'index',
        'action' => 'index'
    );
        
    public function __construct(){
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = new Zend_Acl();
        $this->_resources = new Model_DbTable_Resources;
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $this->loadRoles();
        $this->loadResources();
        $this->loadRules();

        $hasIdentity = $this->_auth->hasIdentity();
        $flashHelper = new Zend_Controller_Action_Helper_FlashMessenger();
        $redirector = new Zend_Controller_Action_Helper_Redirector;
        $translate = Zend_Registry::get('Root_Translate');

        if ($hasIdentity) {
            $info = $this->_auth->getStorage()->read();
            $role = $info->type;
            
            if (!$this->_acl->hasRole($role)) {
                $hasIdentity = false;
                $role = 'guest';
            }
            
        } else {
            $role = 'guest';
        }

        $module = $request->module;
        $controller = $request->controller;
        $action = $request->action;
        
        $resource = $module.':'.$controller;

        if (!$this->_acl->has($resource)) {
            $resource = null;
        }

        if (!$this->_acl->isAllowed($role, $resource, $action)) {
            if (!$hasIdentity) {
                $controller = $this->_noAuth['controller'];
                $action = $this->_noAuth['action'];
                $module = $this->_noAuth['module'];
                $redirector->goToSimple($action, $controller, $module);
            } else {
                $controller = $this->_noAcl['controller'];
                $action = $this->_noAcl['action'];
                $module = $this->_noAcl['module'];
                $flashHelper->setNamespace('errorMessages')
                    ->addMessage($translate->_('permissionDenied'));
                $redirector->gotoSimple($action, $controller, $module);
            }
        }

        $request->setModuleName($module)
                ->setControllerName($controller)
                ->setActionName($action);
    }

    protected function loadRoles() {
        $this->_acl->addRole(new Zend_Acl_Role('administrator'));
        $this->_acl->addRole(new Zend_Acl_Role('manager'));
        $this->_acl->addRole(new Zend_Acl_Role('user'));
        $this->_acl->addRole(new Zend_Acl_Role('guest'));
    }

    protected function loadResources() {
        foreach ($this->_resources->getResources() as $row) {
            $this->_acl->add(new Zend_Acl_Resource($row['module'].':'.$row['controller']));
        }
    }

    protected function loadRules() {
        foreach ($this->_resources->getAcl() as $row) {

            switch ($row['administrator']){
                case 1: $this->_acl->allow('administrator', $row['module'].':'.$row['controller'], array($row['action'])); break;
                case 0: $this->_acl->deny('administrator', $row['module'].':'.$row['controller'], array($row['action'])); break;
            }
            
            switch ($row['manager']){
                case 1: $this->_acl->allow('manager', $row['module'].':'.$row['controller'], array($row['action'])); break;
                case 0: $this->_acl->deny('manager', $row['module'].':'.$row['controller'], array($row['action'])); break;
            }
            
            switch ($row['user']){
                case 1: $this->_acl->allow('user', $row['module'].':'.$row['controller'], array($row['action'])); break;
                case 0: $this->_acl->deny('user', $row['module'].':'.$row['controller'], array($row['action'])); break;
            }

            switch ($row['guest']){
                case 1: $this->_acl->allow('guest', $row['module'].':'.$row['controller'], array($row['action'])); break;
                case 0: $this->_acl->deny('guest', $row['module'].':'.$row['controller'], array($row['action'])); break;
            }

        } 
    }

    public function checkAuth(){
        if ($this->_auth->hasIdentity()) {
            return true;
        } else {
            return false;
        }
    }
    public function getRole(){
        if ($this->checkAuth()){
            $userModel = new User_Model_DbTable_Users();
            $userInfo = $userModel->find( $this->_auth->getStorage()->read()->id )->current();
            return $userInfo['type'];
        } else {
            return false;
        }
    }
}