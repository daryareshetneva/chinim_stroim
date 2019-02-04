<?php
class ItRocks_Form extends Zend_Form {

    public function __construct($options = array())
    {
        parent::__construct($options);
        $translator = new Zend_Translate('array', APPLICATION_PATH.'/langs/');
        Zend_Validate_Abstract::setDefaultTranslator($translator);
    }
}