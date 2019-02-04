<?php

class Model_Tags {
    // field for search
    private $_table = 'Model_DbTable_Resources';

    // interface

    public function getTagsInBootstrap($module) {
        return $this->_getTagsInBootstrap($module);
    }

    public function getTags() {
        return $this->_getTags();
    }
    // by module, controller, action
    public function getTagsByMCA($module, $controller, $action) {
        return $this->_getTagsByMCA($module, $controller, $action);
    }
    // implementation

    private function _getTags() {
        $table = new $this->_table();
        try {
            $resources = $table->getTagsActive();

            $tags = array();
            foreach ($resources as $resource) {
                $curTags = array(
                    'id' => $resource['id'],
                    'alias' => $resource['alias'],
                    'title' => $resource['title'],
                    'metaTitle' => $resource['metaTitle'],
                    'metaDescription'   =>$resource['metaDescription']);
                $tags[$resource['module']]
                        [$resource['controller']]
                        [$resource['action']] = $curTags;
            }

            return $tags;
        } catch (Exception $e) {
            return false;
        }
    }
    private function _getTagsInBootstrap($module) {
        $tables = Zend_Db_Table::getDefaultAdapter()->listTables();

        if (empty($tables)) {
            return false;
        } else {

            $table = new $this->_table();
            $resources = $table->getTagsActiveByModule($module);

            return $resources;
        }
    }
    private function _getTagsByMCA($module, $controller, $action) {
        $table = new $this->_table();
        $resources = $table->getTagsByMCA($module, $controller, $action);

        return $resources;
    }

}
