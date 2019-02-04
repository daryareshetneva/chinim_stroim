<?php


class User_Model_SocialAuth_Google extends User_Model_SocialAuth_Social
{
    const GET_TOKEN_URL = 'https://accounts.google.com/o/oauth2/token';
    const GET_USER_INFO_URL = 'https://www.googleapis.com/oauth2/v1/userinfo';

    public function getToken($responseCode)
    {
        $params = array(
            'client_id'     => $this->_clientId,
            'client_secret' => $this->_clientSecret,
            'redirect_uri'  => $this->_redirectUrl,
            'grant_type'    => 'authorization_code',
            'code'          => $responseCode
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
        $params = [];
        if (isset($this->_token['access_token'])) {
            $params['access_token'] = $this->_token['access_token'];
            $params['alt'] = 'json';

            $response = $this->_requestModel->performRequest(self::GET_USER_INFO_URL, $params);
            $userInfo = json_decode($response, true);
            if (json_last_error() != 0) {
                throw new Exception(json_last_error_msg());
            }
        }

        return $userInfo;
    }

}