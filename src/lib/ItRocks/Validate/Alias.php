<?php

class ItRocks_Validate_Alias extends Zend_Validate_Abstract
{
    const CORRECT_ALIAS = 'aliasError';

    protected
        $_messageTemplates = array
    (
        self::CORRECT_ALIAS => "'%value%' некорректный URL. Можно использовать латинские буквы, цифры и знаки '-', '_'",
    );

    public function isValid($url) {

        $this->_setValue($url);

        $shortTitleRegexp = "/^[0-9a-zA-Z\-\_\']+$/";
        $value = $url;
        if (!preg_match($shortTitleRegexp, $value)) {
            $this->_error(self::CORRECT_ALIAS);
            return false;
        }
        return true;
    }

}
