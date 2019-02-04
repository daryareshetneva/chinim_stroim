<?php


class User_Model_SocialAuth_Vk extends User_Model_SocialAuth_Social
{

    const AUTHORIZE_URL = 'http://oauth.vk.com/authorize';
    const ACCESS_TOKEN_URL = 'https://oauth.vk.com/access_token';
    const USER_INFO_URL = 'https://api.vk.com/method/users.get';

    public function getToken($responseCode)
    {
        $params = array(
            'client_id' => $this->_clientId,
            'client_secret' => $this->_clientSecret,
            'code' => $responseCode,
            'redirect_uri' => $this->_redirectUrl
        );

        $response = $this->_requestModel->performRequest(self::ACCESS_TOKEN_URL, $params);
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
                'uids'         => $this->_token['user_id'],
                'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big,email',
                'access_token' => $this->_token['access_token']
            );

            $response = $this->_requestModel->performRequest(self::USER_INFO_URL, $params);
            $userInfo = json_decode($response, true);
            if (json_last_error() != 0) {
                throw new Exception(json_last_error_msg());
            }
        }
        return $userInfo;
    }
}