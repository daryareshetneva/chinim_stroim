<?php

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('CONFIG_PATH')
	|| define('CONFIG_PATH', (getenv('CONFIG_PATH') ?
			getenv('CONFIG_PATH') : APPLICATION_PATH . '/configs/application.ini'));

defined('APPLICATION_ENV')
	|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

defined('APPLICATION_WIN32')
	|| define('APPLICATION_WIN32', (bool)getenv('APPLICATION_WIN32'));

set_include_path(implode(PATH_SEPARATOR, array(
	realpath(APPLICATION_PATH . '/../lib'),
	get_include_path()
)));

require_once 'Zend/Application.php';

//try {
    $application = new Zend_Application(
            APPLICATION_ENV,
            CONFIG_PATH
    );
    $application->bootstrap()
		->run();
//} catch (Exception $e) {
//    $redirector = new Zend_Controller_Action_Helper_Redirector;
//    $redirector->gotoUrl('/404');
//}

?>