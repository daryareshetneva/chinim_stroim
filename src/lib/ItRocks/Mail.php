<?php

class ItRocks_Mail extends Zend_Mail {
    
    /**
     * OPTIONS for send mail
     */
    const OPTION_AUTH = 'auth';
    const OPTION_AUTH_VALUE = 'login';
    const OPTION_PORT = 'port';
    const OPTION_PORT_VALUE = 25;
    const OPTION_USER = 'username';
    const OPTION_PASS = 'password';
    const OPTION_HOST = 'host';
    const OPTION_SMTP = 'smtp';
    const OPTION_SSL = 'ssl';
    const OPTION_SSL_VALUE = 'tls';
    
    /**
     * ERRORS
     */
    const ERROR_NOT_SET = 'errorNotSetOption_';
    
    /**
     * Options for send mail
     * 
     * array('username' => string, 'password' => string,
     *  'host' => string, 'smtp' => boolean)
     * 
     * @var array 
     */
    protected $_options = array();
    /**
     * Option for send mail
     * 
     * @var string 'example.ru'
     */
    protected $_host = null;
    /**
     * @var string
     */
    protected $_fromName = null;
    
    public function __construct($charset = 'utf-8') {
        parent::__construct($charset);
    }
    
    /*     * **********************************
     * 	    INTERFACE
     * ********************************** */
    
    /**
     * send $subject with $body to $email ($name)
     */
    public function send_m($subject, $body, $email, $name = null) {
        $this->addTo($email, $name);
        $this->_sendEmail($subject, $body);
    }
    
    /**
     * send $subject with $body to $emails
     */
    public function sendMulti($subject, $body, $emails) {
        $this->_sendMultiEmail($subject, $body, $emails);
    }
    
    /**
     * send test message
     */
    public function sendTest() {
        $this->_sendTestEmail();
    }
    
    /**
     * Set options for send mail
     * 
     * array('username' => string, 'password' => string,
     *  'host' => string, 'smtp' => boolean)
     * 
     * @param array $settings
     */
    public function setSettings($settings) {
        $this->_setSettings($settings);
    }
    
    /**
     * Set name from send mail
     * 
     * @param string $settings
     */
    public function setDefaultFromName($name) {
        $this->_setDefaultFromName($name);
    }
    
    /*     * **********************************
     * 	    IMPLEMENTS
     * ********************************** */
    
    private function _sendEmail($subject, $body) {
        $this->setBodyHtml($body);
        $this->setSubject($subject);
        parent::send();
    }
    
    private function _sendMultiEmail($subject, $body, $emails) {
        foreach ($emails as $email) {
            $this->addTo($email);
        }
        $this->_sendEmail($subject, $body);
    }
    
    private function _sendTestEmail() {
        $text = 'Тестовое сообщение';
        $subject = 'Тестовое сообщение';

        $this->addTo($this->_options[self::OPTION_USER]);
        $this->_sendEmail($subject, $text);
    }
    
    private function _setSettings($settings) {
        $this->_checkSettings($settings);
        
        $this->_options = array(
            self::OPTION_AUTH => self::OPTION_AUTH_VALUE,
            self::OPTION_PORT => self::OPTION_PORT_VALUE,
            self::OPTION_USER => $settings[self::OPTION_USER],
            self::OPTION_PASS => $settings[self::OPTION_PASS],
            self::OPTION_SSL => self::OPTION_SSL_VALUE
        );
        
        $this->_host = $settings[self::OPTION_HOST];
        
        if ($settings[self::OPTION_SMTP]) {
            $transport = new Zend_Mail_Transport_Smtp($this->_host, $this->_options);
        } else {
            $transport = new Zend_Mail_Transport_Sendmail($this->_host, $this->_options);
        }
        
        $this->setDefaultTransport($transport);
        
        $this->setDefaultFrom($settings[self::OPTION_USER]);
    }
    
    private function _checkSettings($settings) {
        $fields = array(
            self::OPTION_USER, self::OPTION_PASS,
            self::OPTION_HOST, self::OPTION_SMTP
        );
        
        foreach ($fields as $field) {
            if (!isset($settings[$field])) {
                throw new Exception(self::ERROR_NOT_SET . $field);
            }
        }
    }
    
    private function _setDefaultFromName($name) {
        $default = $this->getDefaultFrom();
        $this->setDefaultFrom($default['email'], $name);
    }
}
