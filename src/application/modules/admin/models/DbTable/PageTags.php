<?php

class Admin_Model_DbTable_PageTags extends Zend_Db_Table_Abstract{
    
    protected $_name = 'PageTags';
    protected $_primary = 'id';
    
    public function getPageTags() {
        /*$select = $this->select()
                       ->from($this->_name, array('title', 'metaTitle', 'metaDescription', 'alias'))
                       ->order('id');
        return $this->getAdapter()->fetchAll($select);*/
        return [];
    }

    public function getByAlias($alias) {
        /*$select = $this->select()
                       ->from($this->_name, array('id'))
                       ->where('alias = ?', $alias);
        $id = $this->getAdapter()->fetchOne($select);
        return $this->find($id)->current();*/
        return [];
    }

}