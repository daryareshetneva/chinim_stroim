<?php

class ItRocks_Validate_Name extends Zend_Validate_Abstract
{
    const CORRECT_NAME = 'nameError';

    protected
        $_messageTemplates = array
    (
        self::CORRECT_NAME => "Некорректное имя",
    );

    public function isValid($data) {

        $this->_setValue($data);
        $shortTitleRegexp = "/^[a-zа-яё\s]+$/iu";

        if (!preg_match($shortTitleRegexp, $data)) {
            $this->_error(self::CORRECT_NAME);
            return false;
        } else {
            return true;
        }
    }
}
