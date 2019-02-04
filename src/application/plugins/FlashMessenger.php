<?php

class Plugin_FlashMessenger extends Zend_Controller_Plugin_Abstract {

    public function postDispatch(Zend_Controller_Request_Abstract $request) {
        $helper = new Zend_Controller_Action_Helper_FlashMessenger();
        $messages = $helper->setNamespace('messages')->getMessages();
        $errorMessages = $helper->setNamespace('errorMessages')->getMessages();
        $warningMessages = $helper->setNamespace('warningMessages')->getMessages();

        if (!$request->isDispatched()) {
            return;
        }

        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();

        if ($messages) {
            $view->messages = $messages;
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
            $viewRenderer->setView($view)->renderScript('messages.phtml',
                    'messages');
        }

        if ($errorMessages) {
            $view->messages = $errorMessages;
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
            $viewRenderer->setView($view)->renderScript('error-messages.phtml',
                    'errorMessages');
        }

        if ($warningMessages) {
            $view->messages = $warningMessages;
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
            $viewRenderer->setView($view)->renderScript('warning-messages.phtml',
                    'warningMessages');
        }
    }

}
