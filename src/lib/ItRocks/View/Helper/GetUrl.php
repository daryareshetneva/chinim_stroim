<?php
class ItRocks_View_Helper_GetUrl extends Zend_Controller_Action_Helper_Abstract {
    /**
     * @param bool $full
     * @return string
     * This function returns url
     * http://example.com/ if $full = false (default)
     * http://example.com/uri if $full = true
     *
     */
    public function getUrl($full = false) {
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }
        $url = $protocol . "://" . $_SERVER['HTTP_HOST'];

        if ($full === true){
            $url = $url.$_SERVER['REQUEST_URI'];
        }
        return $url;
    }
}