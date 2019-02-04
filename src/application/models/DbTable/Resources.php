<?php

class Model_DbTable_Resources extends Zend_Db_Table_Abstract{
    
    protected $_name = 'Resources';
    protected $_primary = 'id';
    
    public function remove($module) {
        $table = new Model_DbTable_Resources;
        
        $where = "module = ?";
        $where = $table->getAdapter()->quoteInto($where, $module);
        
        $table->delete($where);
    }
    
    public function getTagsByModule($module) {
        $select = $this->select()
                ->from($this->_name, array('controller', 'action', 'alias', 'title', 'metaTitle', 'metaDescription'))
                ->where("module = ?", $module);
        
        return $this->getAdapter()->fetchAll($select);
    }
    
    public function getTagsById($id) {        
        return $this->find($id)->current();
    }
    
    public function getTagsActive() {
        $select = $this->select()
                ->from($this->_name, array('id', 'module', 'controller', 'action', 'alias', 'title', 'metaTitle', 'metaDescription'))
                ->where("active = ?", 1)
                ->order('module');
        
        return $this->getAdapter()->fetchAll($select);
    }
    
    public function getTagsActiveByModule($module) {
        $select = $this->select()
                ->from($this->_name, array('id', 'module', 'controller', 'action', 'alias', 'title', 'metaTitle', 'metaDescription'))
                ->where("active = ?", 1)
                ->where("module = ?", $module);
        
        return $this->getAdapter()->fetchAll($select);
    }
    
    public function getTagsByMCA($module, $controller, $action) {
        $select = $this->select()
                ->from($this->_name, array('title', 'metaTitle', 'metaDescription'))
                ->where("active = ?", 1)
                ->where("module = ?", $module)
                ->where("controller = ?", $controller)
                ->where("action = ?", $action);
        
        return $this->getAdapter()->fetchRow($select);
    }
    
    public function getTagsAliases() {
        $select = $this->select()
                ->from($this->_name, array('alias'))
                ->where("alias != ?", '');
        
        return $this->getAdapter()->fetchCol($select);
    }
    
    public function getResources() {
        $select = $this->select()
                ->from($this->_name, array('module', 'controller'))
                ->group(array('module', 'controller'));
        
        return $this->getAdapter()->fetchAll($select);
    }
    
    public function getAcl() {
        $select = $this->select()
                ->from($this->_name, array('module', 'controller', 'action', 'administrator', 'manager', 'user', 'guest'));
        
        return $this->getAdapter()->fetchAll($select);
    }
}