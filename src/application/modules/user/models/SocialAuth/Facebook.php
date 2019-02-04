<?php


class User_Model_SocialAuth_Facebook extends User_Model_SocialAuth_Social
{

    const GET_TOKEN_URL = 'https://graph.facebook.com/oauth/access_token';
    const USER_INFO_URL = 'https://graph.facebook.com/me';

    public function getToken($responseCode)
    {
        $params = array(
            'client_id'     => $this->_clientId,
            'redirect_uri'  => $this->_redirectUrl,
            'client_secret' => $this->_clientSecret,
            'code'          => $responseCode
        );

        $response = $this->_requestModel->performRequest(self::GET_TOKEN_URL, $params);
        parse_str($response, $this->_token);
        if (!isset($this->_token['access_token'])) {
            throw new Exception('Error while getting access token');
        }
        return $this->_token;
    }

    public function getUserInfo()
    {
        $userInfo = [];
        if (isset($this->_token['access_token'])) {
            $params = array(
                'access_token'  => $this->_token['access_token'],
                "fields" => "id,first_name,last_name,email"
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