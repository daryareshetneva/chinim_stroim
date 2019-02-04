<?php

class InvalidNameException extends Exception {
    protected $message = "Вы ввели некорректное имя в форму! Повторите ввод.";
}

class InvalidPhoneException extends Exception {
    protected $message = "Вы ввели некорректный номер телефона в форму! Повторите ввод.";
}

class IndexController extends Zend_Controller_Action {

//    Действие шапки сайта
    public function headerAction() {
        $action = $this->_request->getParam('action');
        $alias = $this->_request->getParam('alias');
        $module = $this->_request->getParam('module');
        $controller = $this->_request->getParam('controller');

        $servicesCatrgoriesTable = new Services_Model_DbTable_Tree();
        $defaultServicesCategory = $servicesCatrgoriesTable->getLimit(1);

        $modules = Bootstrap::getModuleList();
        $this->view->assign('modules', $modules);

        $this->view->assign('action', $action);
        $this->view->assign('controller', $controller);
        $this->view->assign('module', $module);
        $this->view->assign('alias', $alias);
        $this->view->assign('defaultServicesCategory', $defaultServicesCategory);

        // назначение телефона и адреса
        $settingsModel = new Model_Settings();
        $data = $settingsModel->getSettings(['address', 'phone', 'email']);
        $this->view->assign('data', $data);
    }

    public function mainMenuAction() {
        $action = $this->_request->getParam('action');
        $alias = $this->_request->getParam('alias');
        $module = $this->_request->getParam('module');
        $controller = $this->_request->getParam('module');

        $modules = Bootstrap::getModuleList();
        $this->view->assign('modules', $modules);

        $this->view->assign('action', $action);
        $this->view->assign('controller', $controller);
        $this->view->assign('module', $module);
        $this->view->assign('alias', $alias);
    }



    public function socialIconsAction() {
        $settingsModel = new Model_Settings();
        $social     = $settingsModel->getSettings(['vk','facebook', 'twitter', 'odnoklassniki', 'instagram']);
        $this->view->assign('social', $social);
    }

    public function contactsFooterAction() {
        $settingsModel = new Model_Settings();
        $contacts     = $settingsModel->getSettings(['address', 'phone', 'email']);

        $this->view->assign('contacts', $contacts);
    }

    public function contactsBlockAction() {
        $settingsModel = new Model_Settings();
        $contacts     = $settingsModel->getSettings(['address', 'phone', 'email']);

        $this->view->assign('contacts', $contacts);
    }

    public function headerNavTopAction(){
        $settingsModel = new Model_Settings();
        $contacts     = $settingsModel->getSettings(['phone']);

        $this->view->assign('contacts', $contacts);
    }
   
    public function searchAction() {
        $modules = Bootstrap::getModuleList();
        $searchModel = new Model_Search();
        $request = trim($this->getParam('query'));
        $buttonTitle = "buttonSearchTitle";
        $items = [];
        $errorString = "";

        if ($this->_request->isGet()) {
                $buttonTitle = "buttonSearchTryAgain";
                if (strlen($request) > 0) {

                    $items = $searchModel->search($request);
                    if (empty($items)) {
                        $errorString = "resultsNotFound";
                    }
                } else {
                    $errorString = "minimumCharInStringSearch";
                }
        }

        $this->view->assign('modules', $modules);
        $this->view->assign('searchQuery', $request);
        $this->view->assign('searchTitle', $buttonTitle);
        $this->view->assign('items', $items);
        $this->view->assign('errorString', $errorString);
    }

    public function feedbackAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        try {
            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();
                $name = $formData['name'];
                $phone = $formData['phone'];
                if (($this->isNameValid($name)) && ($this->isPhoneValid($phone))) {
                    $feedbackTable = new Static_Model_DbTable_Feedback();
                    $feedback = $feedbackTable->createRow([
                        'phone' => $formData['phone'],
                        'name' => $formData['name'],
                        'date' => date('Y-m-d H:i:s'),
                        'source' => $formData['source'],
                        'message' => $formData['message']
                    ]);
                    $feedback->save();
                    $modelMail = new Model_Mail();
                    $modelMail->sendNewRequest($formData['phone'], $formData['name'], date('Y-m-d H:i:s'), 'Проверьте раздел "Панель" в системе администрирования');
                    echo json_encode(['success' => 'success']);
                }
            }
        }
        catch (InvalidNameException $e) {
            echo json_encode(['nameError' => $e->getMessage()]);
        }
        catch (InvalidPhoneException $e) {
            echo json_encode(['phoneError' => $e->getMessage()]);
        }
        catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function feedbackSuccessAction(){

    }

    public function callUsModalAction(){

    }

    public function footerAction() {
        $action = $this->_request->getParam('action');
        $alias = $this->_request->getParam('alias');
        $module = $this->_request->getParam('module');
        $controller = $this->_request->getParam('controller');

        $servicesCatrgoriesTable = new Services_Model_DbTable_Tree();
        $defaultServicesCategory = $servicesCatrgoriesTable->getLimit(1);

        $modules = Bootstrap::getModuleList();
        $this->view->assign('modules', $modules);

        $this->view->assign('action', $action);
        $this->view->assign('controller', $controller);
        $this->view->assign('module', $module);
        $this->view->assign('alias', $alias);
        $this->view->assign('defaultServicesCategory', $defaultServicesCategory);

        $settingsModel = new Model_Settings();
        $data = $settingsModel->getSettings(['yandexMetrika', 'googleAnalytics', 'address', 'phone', 'email']);
        $this->view->assign('data', $data);

        $settingsModel = new Admin_Model_DbTable_SocialNetworks();
        $vk = $settingsModel->getByTitle('Вконтакте');
        $facebook = $settingsModel->getByTitle('Facebook');
        $instagram = $settingsModel->getByTitle('Instagram');

        $this->view->assign('vk', $vk);
        $this->view->assign('facebook', $facebook);
        $this->view->assign('instagram', $instagram);
    }

    public function sliderAction(){
        $sliderTable = new Static_Model_DbTable_Slider();
        $images = $sliderTable->getSliderImages();
        $this->view->assign('images', $images);
    }

    public function promoblockAction(){
        $sliderTable = new Static_Model_DbTable_Slider();
        $images = $sliderTable->getSliderImages();
        $this->view->assign('images', $images);
    }

    public function achievmentsAction() {
//        $settingsModel = new Model_Settings();
//        $data = $settingsModel->getSettings(['address', 'phone', 'email']);
//        $this->view->assign('data', $data);
    }

    public function callUsPagesAction() {
//        $settingsModel = new Model_Settings();
//        $data = $settingsModel->getSettings(['address', 'phone', 'email']);
//        $this->view->assign('data', $data);
    }

    public function headerLogoAction() {
        $settingsModel = new Model_Settings();
        $logo     = $settingsModel->getSettings(['siteLogo']);

        $this->view->assign('logo', $logo['siteLogo']);
    }

    public function footerLogoAction() {
        $settingsModel = new Model_Settings();
        $logo     = $settingsModel->getSettings(['siteLogo']);

        $this->view->assign('logo', $logo['siteLogo']);
    }

    private function isNameValid($name) {
        if (preg_match("/^[а-яА-ЯёЁ\s]+$/u", $name)) {
            return true;
        } else {
            throw new InvalidNameException();
        }

    }

    private function isPhoneValid($phone) {
        if (preg_match("/[0-9\-\+\s]{6,}$/", $phone)) {
            return true;
        } else {
            throw new InvalidPhoneException();
        }
    }
}