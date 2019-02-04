<?php

class ErrorController extends Zend_Controller_Action {

    public function errorAction() {
        $errors = $this->_getParam('error_handler');

        if ($errors) {
            switch ($errors->type) {
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                default:
                    //need logs
            }
        }

        // 404 error -- controller or action not found
        $this->getResponse()->setHttpResponseCode(404);
        $this->_helper->layout->setLayout('error');

        $this->view->assign('error', $errors);
    }

}