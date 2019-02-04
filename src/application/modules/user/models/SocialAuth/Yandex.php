<?php


class User_Model_SocialAuth_Yandex extends User_Model_SocialAuth_Social
{

    const GET_TOKEN_URL = 'https://oauth.yandex.ru/token';
    const USER_INFO_URL = 'https://login.yandex.ru/info';

    public function getToken($responseCode)
    {
        $params = array(
            'grant_type'    => 'authorization_code',
            'code'          => $responseCode,
            'client_id'     => $this->_clientId,
            'client_secret' => $this->_clientSecret
        );

        $response = $this->_requestModel->performPostRequest(self::GET_TOKEN_URL, $params);
        $this->_token = json_decode($response, true);
        if (json_last_error() != 0) {
            throw new Exception(json_last_error_msg());
        }
        return $this->_token;
    }

    public function getUserInfo()
    {
        $userInfo = [];
        if (isset($this->_token['access_token'])) {
            $params = array(
                'format'       => 'json',
                'oauth_token'  => $this->_token['access_token']
            );

            $response = $this->_requestModel->performPostRequest(self::USER_INFO_URL, $params);
            $userInfo = json_decode($response, true);
            if (json_last_error() != 0) {
                throw new Exception(json_last_error_msg());
            }

        }
        return $userInfo;
    }

}