<?php

/*
 * Auto loading of modules
 */
class Plugin_ModuleLoader extends Zend_Controller_Plugin_Abstract 
{
    protected $_modules;
    protected $_resources;
    protected $_modulePath;
    protected $_db;
    protected $_path;
 
    public function __construct() {
        $this->_modules = new Model_DbTable_Modules;
        $this->_resources = new Model_DbTable_Resources;
        $this->_db = Zend_Db_Table::getDefaultAdapter();
        $this->_modulePath = APPLICATION_PATH . '/modules/';
        $this->_defaultPath = APPLICATION_PATH;
        $this->_path = array(
            'defaultConfig' => $this->_defaultPath . '%s/configs/module.xhtml',
            'config' => $this->_modulePath . '%s/configs/module.xhtml',
            'schema' => $this->_modulePath . '%s/sql/schema.sql',
            'data' => $this->_modulePath . '%s/sql/data.sql',
            'defaultMigrationPath' => $this->_defaultPath . '/misc/migration/',
            'migrationPath' => $this->_modulePath . '%s/sql/migration/'
        );
        $this->check();
    }
        
    /**
     * Load modules information
     * If current module not exists in database sets schema and data 
     * If current module version is above tnen version in database executes migration
     * @param Zend_Controller_Request_Abstract $request
     */
    protected function check() {
        // default check
        $folder = '';
        $main = 'default';
        $config = new Zend_Config_Xml(sprintf($this->_path['defaultConfig'], $folder), 'module');
        $module = $this->_modules->getModule($main);
        if (!empty($config->name) && !empty($config->version)) {
            if (!$module) {
                $newModule = $this->_modules->fetchNew();
                $newModule->name = $main;
                $newModule->version = $config->version;
                $newModule->save();
                // Set schema
                for ($i = ($newModule->version + 1); $i <= $config->version; $i++) {                                    
                    $this->_migration($main, $i, $main);
                }
                $this->_resources->remove($main);
                $this->_setResources($main, $config->resources->toArray());
            } else {
                $this->_checkModule($main, $config, $module);
            }
        }
        
        $modules = new DirectoryIterator($this->_modulePath);
        foreach ($modules as $folderName) {
            $folder = $folderName->getFileName();
            if ($folder != '.' && $folder != '..') {
                if (file_exists(sprintf($this->_path['config'], $folder))) {
                    $config = new Zend_Config_Xml(sprintf($this->_path['config'], $folder), 'module');
                    $module = $this->_modules->getModule($folder);
                    Bootstrap::addModule($config->name, $config->status);
                    if (!empty($config->name) && !empty($config->version)) {
                        if ($config->status) {


                            if (!$module) { // Module not exist
                                $newModule = $this->_modules->fetchNew();
                                $newModule->name = $folder;
                                $newModule->version = $config->version;
                                $newModule->save();
                                // Set schema
                                if (file_exists(sprintf($this->_path['schema'], $folder))) $this->_setSchema($folder);
                                // Insert data
                                if (file_exists(sprintf($this->_path['data'], $folder))) $this->_insertModuleData ($folder);
                                // Add resources
                                if (is_object($config->resources)) $this->_setResources($folder, $config->resources->toArray());

                            } else {
                                $this->_checkModule($folder, $config, $module);
                            }
                        }
                    }
                }
            }
        }
    }
    
    protected function _checkModule($folder, $config, $module) {     
        
        if ($config->version > $module['version']) {
            // Run migration
            for ($i = ($module['version'] + 1); $i <= $config->version; $i++) {                                    
                $this->_migration($folder, $i, $module['name']);
            }
            // Update resources
            if (is_object($config->resources)) {
                $this->_setResources($folder, $config->resources->toArray());
            }
            // Update module version
            $module = $this->_modules->find($folder)->current();
            $module->version = $config->version;
            $module->save();
        } else if ($config->version < $module['version']) {
            // Throw exception
            throw new Exception('Module' . $module['name'] . ' has wrong version');
        }
    }
    
    
    /**
     * Installing sql schema to database
     * @param string $folder module folder
     */
    protected function _setSchema($folder)
    {
        $sql = file_get_contents(realpath(sprintf($this->_path['schema'], $folder)));
        try {
            foreach (explode(';', $sql) as $subsql) {
                $subsql = trim($subsql);
                if ($subsql) {
                    $this->_db->query($subsql);
                }
            }
        } catch(Exception $e) {
            throw new Zend_Exception("Module set schema error: {$e->getMessage()}");
        }
    }
    
    /**
     * Installing sql data to database
     * @param string $folder module folder
     */
    protected function _insertModuleData($folder) 
    {
        $sql = file_get_contents(realpath(sprintf($this->_path['data'], $folder)));
        try {
            foreach (explode(');', $sql) as $subsql) {
                $subsql = trim($subsql);
                if ($subsql) {
                    $this->_db->query(new Zend_Db_Expr($subsql  . ');'));
                }
            }
        } catch(Exception $e) {
            throw new Zend_Exception("Module inset data error: {$e->getMessage()})");
        }
    }
    
    /**
     * Module table migration
     * @param string $folder
     * @throws Zend_Exception
     */
    protected function _migration($folder, $migrationNumber, $moduleName)
    {
        if ($folder == 'default') {
            $filename = $this->_path['defaultMigrationPath'] . $migrationNumber . '.php';
        } else {
            $filename = sprintf($this->_path['migrationPath'], $folder) . $migrationNumber . '.php';
        }
        
        if (!file_exists($filename))
            throw new Exception("Migration file {$migrationNumber}.php absent for module '{$moduleName}'");
        try {
            return include $filename;
        } catch(Exception $e) {
            throw new Zend_Exception("Module migration error: {$e->getMessage()}. Migration number: {$migrationNumber}. Module name '{$moduleName}'");
        }
    }
    /**
     * Installing resources to database
     * @param string $module
     * @param array $resources
     * @throws Zend_Exception
     */
    protected function _setResources ($module, $resources)
    {
        try {
            $resultGet = $this->_resources->getTagsByModule($module);
            $curResources = array();
            foreach ($resultGet as $result) {
                $curResources[$result['controller']][$result['action']] = array(
                    'alias' => $result['alias'],
                    'title' => $result['title'],
                    'metaTitle' => $result['metaTitle'],
                    'metaDescription' => $result['metaDescription']);
            }
            
            $this->_resources->remove($module);
                        
            foreach ($resources as $controllers => $actions) {
                $k = 0;                
                foreach ($actions as $action) {
                    $admin[$k] = $action['administrator'];
                    $manager[$k] = $action['manager'];
                    $user[$k] = $action['user'];
                    $guest[$k] = $action['guest'];
                    $active[$k] = $action['active'];
                    $k++;
                }
                
                $a = array_keys($actions);
                $i = 0;
                foreach ($a as $action){
                    $row = $this->_resources->fetchNew();
                    $row->module = strtolower($module);
                    $row->controller = $controllers;
                    $row->action = $action;
                    $row->administrator = $admin[$i];
                    $row->manager = $manager[$i];
                    $row->user = $user[$i];
                    $row->guest = $guest[$i];
                    $row->active = $active[$i];

                    if (isset($curResources[$controllers][$action])) {
                        $row->alias = $curResources[$controllers][$action]['alias'];
                        $row->title = $curResources[$controllers][$action]['title'];
                        $row->metaTitle = $curResources[$controllers][$action]['metaTitle'];
                        $row->metaDescription = $curResources[$controllers][$action]['metaDescription'];
                    }
                    $row->save();
                    $i++;
                }
            }
        } catch (Exception $e) {
            throw new Zend_Exception ('Module resources upload error');
        }
    }
}