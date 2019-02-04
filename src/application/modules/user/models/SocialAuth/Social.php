<?php


abstract class User_Model_SocialAuth_Social
{

    protected $_clientSecret = '';
    protected $_clientId = '';
    protected $_redirectUrl = '';
    protected $_requestModel = null;
    protected $_token = '';

    public function __construct($clientSecret, $clientId, $redirectUrl, $requestModel)
    {
        $this->_clientSecret = $clientSecret;
        $this->_clientId = $clientId;
        $this->_redirectUrl = $redirectUrl;
        $this->_requestModel = $requestModel;
    }

    abstract function getToken($responseCode);

    abstract function getUserInfo();
}