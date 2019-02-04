<?php

if(!function_exists('mime_content_type')) {

    function mime_content_type($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
}

class Model_Mail {

    protected $_options = array();
    protected $_smtp = 0;
    protected $_host = '';
    protected $_mail;
    protected $_fromName = 'Веб-студия ItRocks';
    protected $_translate;
    protected $_bodyTemplates = array(
        'template' => 'mail/template.html',
        'afterRegistration' => 'mail/user_after_registration.html',
        'afterReplyQuestion' => 'mail/user_after_reply_question.html',
        'forgetPassword' => 'mail/user_forget_password.html',
        'managerNewOrder' => 'mail/manager_new_order.html',
        'managerOrderCancel' => 'mail/manager_order_cancel.html',
        'managerProductCancel' => 'mail/manager_product_cancel.html',
        'userNewOrder' => 'mail/user_new_order.html',
        'userEditedOrder' => 'mail/user_edited_order.html',
        'userEditedDiscount' => 'mail/user_edited_discount.html',
        'managerUserQuery' => 'mail/manager_user_query.html',
        'managerUserQuestion' => 'mail/manager_user_question.html',
        'distribution' => 'mail/distribution.html',
        'userGoodsChangeStatus' => 'mail/user_change_status.html'
    );

    public function __construct() {
        $settingsTable = new Model_DbTable_Settings;
        $settings = $settingsTable->getEmailSettings();
        $this->_translate = Zend_Registry::get('Root_Translate');
        $this->_options = array(
            'ssl' => 'tls',
            'auth' => 'login',
            'username' => $settings['mailUsername'],
            'password' => $settings['mailPassword'],
            'port' => 25,
        );
        $this->_host = $settings['mailHost'];
        $this->_smtp = $settings['mailSmtp'];
        if ($settings['mailSmtp']) {
            $transport = new Zend_Mail_Transport_Smtp($this->_host,
                    $this->_options);
        } else {
            $transport = new Zend_Mail_Transport_Sendmail($this->_host,
                    $this->_options);
        }
        Zend_Mail::setDefaultTransport($transport);

        $this->_mail = new Zend_Mail('utf-8');
    }

// ============================
// ========== PUBLIC ==========
// ============================
 
    public function sendDistribution($subject, $title, $body, $file, $users) {
        $mailBody = $this->_getTemplate('distribution');
        $mailBody = str_replace('%body%', $body, $mailBody);
        $mailBody = str_replace('%title%', $title, $mailBody);
        
        foreach ($users as $user) {
            if ($this->_checkAndReplaceUnsubscribe($mailBody, $user)) {
                $this->_sendToUserWithDistribution($subject, $user['email'], $user['fio'], $mailBody, $file);
            }
        }
    }
    
    public function checkOptions() {
        if ($this->_options['username'] != null &&
                $this->_options['password'] != null &&
                $this->_host != null && $this->_smtp != null) {
            return true;
        }
        return false;
    }

    public function setAuthOptions($username, $password) {
        $this->_options['username'] = $username;
        $this->_options['password'] = $password;
    }

    public function setHost($host) {
        $this->_host = $host;
    }

    public function setFromName($name) {
        $this->_fromName = $name;
    }

    public function sendTestEmail() {
        $this->initMail();
        $text = 'Тестовое сообщение';
        $subject = 'Тестовое сообщение';

        $this->_mail->setBodyHtml($text);
        $this->_mail->setFrom($this->_options['username'], $this->_fromName);
        $this->_mail->addTo($this->_options['username'], 'User');
        $this->_mail->setSubject($subject);
        $this->_mail->send();
    }

    public function sendNewPassword($email, $password) {
        $this->initMail();
        $text = 'Ваш новый пароль ' . $password;
        $subject = 'Восстановление пароля';

        $this->_mail->setBodyHtml($text);
        $this->_mail->setFrom($this->_options['username'], $this->_fromName);
        $this->_mail->addTo($email, $email);
        $this->_mail->setSubject($subject);
        $this->_mail->send();
    }

    public function sendNewRequest($contacts, $name, $date, $message) {
        $settingsTable = new Model_DbTable_Settings();
        $settings = $settingsTable->getSettings(['email']);
        $this->initMail();
        $subject = 'Новая заявка Веб-студия ItRocks';

        $body = "<html><body><p>Вы получили новую заявку через форму на сайте.</p>";
        $body .= "<br>ФИО: " . $name;
        $body .= "<br>Контактные данные: " . $contacts;
        $body .= "<br>Дата: " . $date;
        if ($message) {
            $body .= "<br>Сообщение: " . $message;
        }

        $this->_mail->setBodyHtml($body);
        $this->_mail->setFrom($this->_options['username'], $this->_fromName);
        $this->_mail->setSubject($subject);
        $this->_mail->addTo($settings[0]['value'], $settings[0]['value']);
        $this->_mail->send();
    }
// ========= FAQ =========

    public function sendEditDiscountToUser($formData) {
        $body = $this->_getTemplate('userEditedDiscount');

        $body = str_replace('%discount%', $formData['discount'], $body);

        $subject = $this->_translate->_('UserEditedDiscount');
        $this->_sendToUser($subject, $formData['email'], $formData['fio'], $body);
    }

// ========= FAQ =========

    public function sendAfterReplyQuestion($fio, $email, $answer, $question) {
        $body = $this->_getTemplate('afterReplyQuestion');

        $body = str_replace('%userFio%', $fio, $body);
        $body = str_replace('%answer%', $answer, $body);
        $body = str_replace('%question%', $question, $body);

        $subject = $this->_translate->_('mainSubjectAfterReplyQuestion');
        $this->_sendToUser($subject, $email, $fio, $body);
    }

    public function sendQuestionToManagers($fio, $email, $question) {
        $body = $this->_getTemplate('managerUserQuestion');

        $userModel = new User_Model_Users();
        $managers = $userModel->getManagers();

        $body = str_replace('%userFio%', $fio, $body);
        $body = str_replace('%userEmail%', $email, $body);
        $body = str_replace('%question%', $question, $body);

        $subject = $this->_translate->_('mainSubjectQuestionToManagers');
        $this->_sendToManagers($subject, $managers, $body);
    }

// ========= REGISTRATION =========
    public function sendBeforeRegistration($email, $pass, $fio, $phone) {
        $body = $this->_getTemplate('afterRegistration');

        $body = str_replace('%userEmail%', $email, $body);
        $body = str_replace('%userPass%', $pass, $body);
        $body = str_replace('%userFio%', $fio, $body);
        $body = str_replace('%userPhone%', $phone, $body);

        $subject = $this->_translate->_('RegistrationSuccess');
        $this->_sendToUser($subject, $email, $fio, $body);
    }

    public function sendForgetPassword($email, $href) {
        $body = $this->_getTemplate('forgetPassword');

        $body = str_replace('%userHref%', $href, $body);

        $subject = $this->_translate->_('ForgetPassword');
        $this->_sendToUser($subject, $email, 'User', $body);
    }

// ========= ORDER =========
    public function sendCancelOrderToManagers($orderId) {
        $body = str_replace('%orderId%', $orderId,
                $this->_getTemplate('managerOrderCancel'));

        $subject = $this->_translate->_('managerOrderCancel');
        $userModel = new User_Model_Users();
        $managers = $userModel->getManagers();
        $this->_sendToManagers($subject, $managers, $body);
    }
    
    public function sendCancelProductToManagers($orderId) {
        $body = str_replace('%orderId%', $orderId,
                $this->_getTemplate('managerProductCancel'));

        $subject = $this->_translate->_('managerProductCancel');
        $userModel = new User_Model_Users();
        $managers = $userModel->getManagers();
        $this->_sendToManagers($subject, $managers, $body);
    }

    public function sendNewOrderToManagers(
            $managers, $products, $orderId, $user, $formData, $delivery
    ) {
        $body = $this->_getTemplate('managerNewOrder');

        $productsString = $this->_getProductsHtml($products);
        $totalSumm = $this->_getTotalSumm($products);

        $body = str_replace('%orderId%', $orderId, $body);
        $body = str_replace('%products%', $productsString, $body);
        $body = str_replace('%totalSumm%', $totalSumm, $body);

        $body = str_replace('%userFio%', $formData['fio'], $body);
        $body = str_replace('%userPhone%', $formData['phone'], $body);
        $body = str_replace('%userEmail%', $formData['email'], $body);

        if ($user['status'] == 'normal') {
            $status = "Физическое лицо";
        } else {
            $status = "Юридическое лицо";
        }

        $body = str_replace('%userStatus%', $status, $body);

        $subject = $this->_translate->_('ManagerNewOrder');
        $this->_sendToManagers($subject, $managers, $body);
    }

    public function sendNewOrderToUser($products, $orderId, $formData) {
        $body = $this->_getTemplate('userNewOrder');

        $productsString = $this->_getProductsHtml($products);
        $totalSumm = $this->_getTotalSumm($products);

        $body = str_replace('%orderId%', $orderId, $body);
        $body = str_replace('%products%', $productsString, $body);
        $body = str_replace('%totalSumm%', $totalSumm, $body);

        $subject = $this->_translate->_('UserNewOrder');
        $this->_sendToUser($subject, $formData['email'], $formData['fio'], $body);
    }

    public function sendEditedOrderToUser($order, $status) {
        $body = $this->_getTemplate('userEditedOrder');

        $productsString = $this->_getProductsHtml($order['goods']);
        $totalSumm = $this->_getTotalSumm($order['goods']);

        $body = str_replace('%orderId%', $order['id'], $body);
        $body = str_replace('%status%', $this->_translate->_($status), $body);
        $body = str_replace('%products%', $productsString, $body);
        $body = str_replace('%totalSumm%', $totalSumm, $body);

        $subject = $this->_translate->_('UserEditedOrder');
        $this->_sendToUser($subject, $order['email'], $order['fio'], $body);
    }

    public function sendChangedGoodsStatusToUser($order, $products) {
        $body = $this->_getTemplate('userGoodsChangeStatus');

        $productsString = $this->_getProductsWithStatusesHtml($products);
        $totalSumm = $this->_getTotalSummWithStatuses($products);

        $body = str_replace('%orderId%', $order['id'], $body);        
        $body = str_replace('%products%', $productsString, $body);
        $body = str_replace('%totalSumm%', $totalSumm, $body);

        $subject = $this->_translate->_('UserEditedOrder');
        $this->_sendToUser($subject, $order['email'], $order['userFio'], $body);
    }

// ========= USER QUERY =========
    public function sendQueryToManagers($car, $question, $user) {
        $userModel = new User_Model_Users();
        $managers = $userModel->getManagers();

        $body = $this->_getTemplate('managerUserQuery');

        // user
        $body = str_replace('%userFio%', $user['fio'], $body);
        $body = str_replace('%userPhone%', $user['phone'], $body);
        $body = str_replace('%userEmail%', $user['email'], $body);

        // car
        $body = str_replace('%carVehicle%', $car[db_UserCars::_VEHICLE], $body);
        $body = str_replace('%carModel%', $car[db_UserCars::_MODEL], $body);
        $body = str_replace('%carVIN%', $car[db_UserCars::_VIN], $body);
        $body = str_replace('%carYear%', $car[db_UserCars::_YEAR], $body);
        $body = str_replace('%carEngine%', $car[db_UserCars::_ENGINE_VOL], $body);
        switch ($car[db_UserCars::_DRIVE]) {
            case db_UserCars::DRIVE_FULL:
                $drive = 'Полный привод';
                break;
            case db_UserCars::DRIVE_FRONT:
                $drive = 'Передний привод';
                break;
            case db_UserCars::DRIVE_BACK:
                $drive = 'Задний привод';
                break;
        }
        $body = str_replace('%carDrive%', $drive, $body);
        $body = str_replace('%carPower%', $car[db_UserCars::_POWER], $body);

        $body = str_replace('%question%', $question, $body);


        $subject = $this->_translate->_('ManagerNewQuery');
        $this->_sendToManagers($subject, $managers, $body);
    }

    public function sendRequestFeedbackFormToAdmin($userData, $baseUrl) {
        $this->initMail();
        $subject = 'Новое сообщение с формы обратного звонка';
//        $body = $this->_getTemplate('layout');

        $usersTable = new User_Model_DbTable_Users;
        $adminEmail = $usersTable->getAdminEmail();

        $body = str_replace('%baseUrl%', $baseUrl, $body);
        $body = str_replace('%TITLE%', $subject, $body);
        $content = 'Имя: ' . $userData['name'];
        $content .= "\n\rТелефон: " . $userData['email'];
        $content .= "\n\rДата: " . date('d.m.Y H:i', strtotime($userData['date']));
        $content .= "\"\n\r".$userData['message'];
        $body = str_replace('%CONTENT%', $content, $body);


        $this->_mail->setBodyHtml($content);
        $this->_mail->setFrom($this->_options['username'], $this->_fromName);
        $this->_mail->addTo($adminEmail, $this->_fromName);
        $this->_mail->setSubject($subject);
        $this->_mail->send();
    }

// ============================
// ========= PROTECTED ========
// ============================
    private function initMail() {
        $this->_mail = new Zend_Mail('utf-8');
    }

//    protected function _sendTemplate() {
//        $body = $this->_getTemplate('template');
//
//        $this->_sendToUser('Template', 'alexandr.vic-rimer@ItRocksdev.ru',
//                'Brain Brainovich', $body);
//    }

    protected function _sendToUser($subject, $sendTo, $userFio, $body) {
        $this->initMail();
        $this->_mail->setBodyHtml($body);
        $this->_mail->setFrom($this->_options['username'], $this->_fromName);
        $this->_mail->addTo($sendTo, $userFio);
        $this->_sender($subject);
    }
    
    protected function _sendToUserWithDistribution($subject, $sendTo, $userFio, $body, $file) {
        $this->initMail();
        $this->_mail->setBodyHtml($body);
        $this->_mail->setFrom($this->_options['username'], $this->_fromName);
        $this->_mail->addTo($sendTo, $userFio);
        if (!empty($file)) {
            
            $filename = $this->uploadFile(
                    $file['tmp_name'],
                    $file['name'],
                    'tmp'
            );
            $at = new Zend_Mime_Part(file_get_contents($filename));
            $at->type        = mime_content_type($filename);
            $at->disposition = Zend_Mime::DISPOSITION_INLINE;
            $at->encoding    = Zend_Mime::ENCODING_BASE64;
            $at->filename    = $file['name'];

            $this->_mail->addAttachment($at);
        }
        $this->_sender($subject);
    }
    
    private function uploadFile($tmpFile, $file, $path) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $filename = date('Ymdhis') . substr((string)microtime(), 2, 8);
    
        if (!move_uploaded_file($tmpFile, $filepath = $path . DIRECTORY_SEPARATOR . $filename . '.' . $extension))
            throw new Exception('uploadError');
        
        return $filepath; 
    }

    protected function _sendToManagers($subject, $managers, $body) {
        $this->initMail();
        $this->_mail->setBodyHtml($body);
        $this->_mail->setFrom($this->_options['username'], $this->_fromName);
        $this->_mail->addTo($this->_options['username'], $this->_fromName);
        foreach ($managers as $manager) {
            $this->_mail->addCc($manager['email'], $manager['email']);
        }
        $this->_sender($subject);
    }

    protected function _sender($subject) {
        $this->_mail->setSubject($subject);
        $this->_mail->addHeader('X-MIME-Version: 1.0');
        $this->_mail->addHeader('Content-type:text/html;charset=utf-8"');
        $this->_mail->send();
    }

    protected function _getProductsHtml($products) {
        $productsString = '';
        foreach ($products as $product) {
            $subTotal = $product['amount'] * $product['cost'];
            $productsString .= '<tr>';
            $productsString .= '<td style="text-align: center; border: 1px solid gray;">';
            $productsString .= $product['article'];
            $productsString .= '</td>';
            $productsString .= '<td style="text-align: left; border: 1px solid gray;">';
            $productsString .= $product['title'];
            $productsString .= '</td>';
            $productsString .= '<td style="text-align: center; border: 1px solid gray;">';
            $productsString .= $product['amount'];
            $productsString .= '</td>';
            $productsString .= '<td style="text-align: center; border: 1px solid gray;">';
            $productsString .= $subTotal;
            $productsString .= '</td>';
            $productsString .= '</tr>';
        }

        return $productsString;
    }

    protected function _getProductsWithStatusesHtml($products) {
        $productsString = '';
        foreach ($products as $product) {
            $productsString .= '<tr>';
            $productsString .= '<td style="text-align: center; border: 1px solid gray;">';
            $productsString .= $product['article'];
            $productsString .= '</td>';
            $productsString .= '<td style="text-align: left; border: 1px solid gray;">';
            $productsString .= $product['title'];
            $productsString .= '</td>';
            $productsString .= '<td style="text-align: center; border: 1px solid gray;">';
            $productsString .= $this->_translate->_('orderIs' . $product['status']);
            $productsString .= '</td>';
            $productsString .= '<td style="text-align: center; border: 1px solid gray;">';
            $productsString .= $product['cost'];
            $productsString .= '</td>';
            $productsString .= '</tr>';
        }

        return $productsString;
    }

    protected function _getTotalSumm($products) {
        $total = 0;
        foreach ($products as $product) {
            $subTotal = $product['amount'] * $product['cost'];
            $total += $subTotal;
        }
        return $total;
    }

    protected function _getTotalSummWithStatuses($products) {
        $total = 0;
        foreach ($products as $product) {
            $subTotal = $product['price'];
            $total += $subTotal;
        }
        return $total;
    }
    
    private function _checkAndReplaceUnsubscribe(&$body, $user) {
        if (empty($user['hash'])) {
            $userModel = new User_Model_Users();
            $userModel->initHash($user['email']);
            $user = $userModel->getUserByEmail($user['email']);
        }
        $body = str_replace('%unsubscribe%', $this->_getUnsubscribeLink($user['hash']), $body);
        return true;
    }
    
    private function _getUnsubscribeLink($hash) {
        $link = $this->_translate->_('unsubscribeLink');
        return str_replace('%hash%', $hash, $link);
    }

    private function _getTemplate($name) {
        $body = file_get_contents($this->_bodyTemplates[$name]);
        return str_replace('%baseUrl%', $this->_getBaseUrl(), $body);
    }

    private function _getBaseUrl() {
        $frontController = Zend_Controller_Front::getInstance();
        return $frontController->getRequest()->getHttpHost() . $frontController->getBaseUrl();
    }
    
    

}