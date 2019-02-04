<?php

class Validator_Url extends Zend_Validate_Abstract
{
    const INVALID_URL = 'invalidUrl';

    protected $_messageTemplates = array
    (
        self::INVALID_URL => "'%value%' не валидная ссылка.",
    );

    public function isValid($value)
    {
        $valueString = (string) $value;
        $this->_setValue($valueString);

        $request = Zend_Controller_Front::getInstance()->getRequest();
        
        if (!Zend_Uri::check($request->getScheme() . "://" . $request->getHttpHost(). $value))
        {
                $this->_error(self::INVALID_URL);
                return false;
        }
        return true;
    }
}
