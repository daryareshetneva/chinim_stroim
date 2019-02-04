<?php

class ItRocks_View_Helper_UrlDynamic extends Zend_View_Helper_Url {

    // get param const
    const GETSTART = '?';
    const GETSEPERATOR = '&';
    // error const
    const ERROR_SETURL = 'errorInSetUrl';
    const ERROR_EMPTYURL = 'errorEmptyUrl';

    protected $_url = null;

    public function urlDynamic(
            array $urlOptions = array(), $name = null, $reset = false,
            $encode = false, $hostUrl = null
    ) {
        $this->_url = $this->_getUrl($name, $reset, $hostUrl);
        $this->_url .= $this->_getParams($urlOptions, $encode);

        return $this->_url;
    }

    private function _getParams($urlOptions, $encode) {
        $params = "";
        if ($urlOptions) {
            $notFirstFlag = false;
            foreach ($urlOptions as $key => $value) {
                $param = null;
                if ($notFirstFlag) {
                    $params .= self::GETSEPERATOR;
                } else {
                    $notFirstFlag = true;
                }
                if (is_array($value)) {
                    $params .= $this->_getArrayParam($key, $value);
                } else {
                    $param = $key . '=' . $value;
                }
                $params .= $param;
            }

            if ($encode) {
                $params = urlencode($params);
            }
        }

        return self::GETSTART . $params;
    }
    
    private function _getArrayParam($name, $param) {
        $params = null;
        $len = count($param);
        for ($i = 0; $i < $len; $i++) {
            $params .= $name . '=' . $param[$i];
            if ( $i + 1 != $len) {
                $params .= self::GETSEPERATOR;
            }
        }
        return $params;
    }

    private function _getUrl($name, $reset, $hostUrl) {
        try {
            $url = $hostUrl . parent::url(array(), $name, $reset, false);
        } catch (Exception $e) {
            throw new Exception(self::ERROR_SETURL);
        }

        if (empty($url)) {
            throw new Exception(self::ERROR_EMPTYURL);
        }

        return $url;
    }

}
