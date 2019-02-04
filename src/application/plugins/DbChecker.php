<?php

class Plugin_DbChecker extends Zend_Controller_Plugin_Abstract 
{
    protected
        $_bootstrap = null,
        $_scriptPath = null,
        $_dbConfig = null,
        $_backupPath = null;

    public
        $db = null;

    public function __construct(Zend_Application_Bootstrap_BootstrapAbstract $bootstrap) {
        $bootstrap->bootstrap('db');
        $this->db = $bootstrap->db;
        $this->_dbConfig = $this->db->getConfig();
        $this->_backupPath = $bootstrap->getOption('db');
        $this->_scriptsPath = realpath(APPLICATION_PATH . '/misc');
        $this->check();
    }

    protected function check() {
        $tables = $this->db->fetchCol('SHOW TABLES');
        foreach ($tables as &$table) {
            $table = strtolower($table);
        }
        // If database structure doesn't exist
        if (!in_array('modules', $tables)) {
            $this->_createSchema();
            $this->_insertData();
        }

    }

    protected function _createSchema() {
        $sql = file_get_contents(realpath($this->_scriptsPath . '/schema.sql'));

        foreach (explode(';', $sql) as $subsql) {
            $subsql = trim($subsql);
            if ($subsql) {
                $this->db->query($subsql);
            }
        }
    }

    protected function _insertData() {
        $sql = file_get_contents(realpath($this->_scriptsPath . '/data.sql'));

        foreach (explode(';', $sql) as $subsql) {
            $subsql = trim($subsql);
            if ($subsql) {
                    $this->db->query($subsql);
            }
        }
    }
}