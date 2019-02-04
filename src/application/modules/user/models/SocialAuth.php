<?php


class User_Model_SocialAuth
{
    const TYPE_VKONTAKTE = 'vk';
    const TYPE_FACEBOOK = 'fb';
    const TYPE_GOOGLE = 'google';
    const TYPE_YANDEX = 'yandex';

    const VKONTAKTE_AUTH_URL = 'http://oauth.vk.com/authorize';
    const FACEBOOK_AUTH_URL = 'https://www.facebook.com/dialog/oauth';
    const GOOGLE_AUTH_URL = 'https://accounts.google.com/o/oauth2/auth';
    const YANDEX_AUTH_URL = 'https://oauth.yandex.ru/authorize';


    public function getAuthUrl($socialType, $clientId, $secret, $redirectUrl) {
        $url = '';
        if (empty($clientId) || empty($secret)) {
            return '';
        }
        switch ($socialType) {
            case self::TYPE_VKONTAKTE:
                $url = $this->_getVkAuthUrl($clientId, $secret, $redirectUrl);
                break;
            case self::TYPE_FACEBOOK:
                $url = $this->_getFbAuthUrl($clientId, $secret, $redirectUrl);
                break;
            case self::TYPE_GOOGLE:
                $url = $this->_getGoogleAuthUrl($clientId, $secret, $redirectUrl);
                break;
            case self::TYPE_YANDEX:
                $url = $this->_getYandexAuthUrl($clientId, $secret, $redirectUrl);
                break;
            default:
                $url = '';
        }
        return $url;
    }

    private function _getVkAuthUrl($clientId, $secret, $redirectUrl) {
        $params = array(
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectUrl,
            'response_type' => 'code',
            'scope' => 'first_name,last_name,email',
            'display' => 'popup'
        );

        return self::VKONTAKTE_AUTH_URL . '?' . urldecode(http_build_query($params));
    }

    private function _getFbAuthUrl($clientId, $secret, $redirectUrl) {
        $params = array(
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectUrl,
            'response_type' => 'code',
            'scope'         => 'email,user_birthday'
        );
        return self::FACEBOOK_AUTH_URL . '?' . urldecode(http_build_query($params));
    }

    private function _getGoogleAuthUrl($clientId, $secret, $redirectUrl) {
        $params = array(
            'redirect_uri'  => $redirectUrl,
            'response_type' => 'code',
            'client_id'     => $clientId,
            'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
        );

        return self::GOOGLE_AUTH_URL . '?' . urldecode(http_build_query($params));
    }

    private function _getYandexAuthUrl($clientId, $secret, $redirectUrl) {
        $params = array(
            'response_type' => 'code',
            'client_id'     => $clientId,
            'display'       => 'popup'
        );
        return self::YANDEX_AUTH_URL . '?' . urldecode(http_build_query($params));
    }


}