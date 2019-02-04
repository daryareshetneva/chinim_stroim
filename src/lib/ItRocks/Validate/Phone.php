<?php

class ItRocks_Validate_Phone extends Zend_Validate_Abstract {

    const ERROR_PHONE = 'phoneError';

    protected
        $_messageTemplates = array
    (
        self::ERROR_PHONE => "Неправильно указан номер телефона.",
    );

    public function isValid($data) {

        $this->_setValue($data);

        $shortTitleRegexp = "/^[0-9\s\-\+\(\)]+$/";
        $value = $data;
        if (!preg_match($shortTitleRegexp, $value)) {
            $this->_error(self::ERROR_PHONE);
            return false;
        }
        return true;
    }

}
?>