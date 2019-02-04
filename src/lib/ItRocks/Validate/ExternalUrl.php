<?php

class ItRocks_Validate_ExternalUrl extends Zend_Validate_Abstract {

    const EXTERNAL_URL = 'urlError';

    protected
        $_messageTemplates = array
    (
        self::EXTERNAL_URL => "'%value%' некорректный URL.",
    );

    public function isValid($data) {

        $this->_setValue($data);

        $shortTitleRegexp = "/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/";
        $value = $data;
        if (!preg_match($shortTitleRegexp, $value)) {
            $this->_error(self::EXTERNAL_URL);
            return false;
        }
        return true;
    }

}
?>