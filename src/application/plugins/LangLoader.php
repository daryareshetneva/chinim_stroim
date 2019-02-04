<?php
/**
 *Set language folder for current module
 */
class Plugin_LangLoader extends Zend_Controller_Plugin_Abstract {
    
    function preDispatch(Zend_Controller_Request_Abstract $request) {
        $module = $request->module;
        $locale = new Zend_Locale('ru_RU');
        Zend_Registry::set('Zend_Locale', $locale);
        
        $rootTranslate = new Zend_Translate(
                'ini', 
                APPLICATION_PATH.'/langs/ru.ini',
                $locale, 
                array('scan' => Zend_Translate::LOCALE_FILENAME)
                );

        Zend_Registry::set('Root_Translate', $rootTranslate);
        
        if ($module != 'default') {
            $translate = new Zend_Translate(
                    'ini',
                    APPLICATION_PATH.'/modules/'.$module.'/langs/ru.ini',
                    $locale,
                    array('scan' => Zend_Translate::LOCALE_FILENAME)
                    );           

            Zend_Registry::set('Zend_Translate', $translate);  
        }
    }
}