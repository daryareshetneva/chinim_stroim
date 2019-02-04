<?php
class Model_Settings{

    public function getSettings($fields = ['title','keywords', 'description', 'siteLogo', 'siteFavicon']) {
        $settingsTable = new Model_DbTable_Settings;

        $settings = $settingsTable->getSettings($fields);
        $result = [];
        foreach ($settings as $id => $ar) {
                $result[$ar['name']] = $ar['value'];
        }
        return $result;
    }

    public function updateMainSettings($formData) {
        $settingsDbTable = new Model_DbTable_Settings();
        foreach ($formData as $key => $value){
            $settingsDbTable->updateSettings($key,$value);
        }
    }
}