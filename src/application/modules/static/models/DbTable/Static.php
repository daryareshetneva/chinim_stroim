<?php

class Static_Model_DbTable_Static extends Zend_Db_Table_Abstract{
    
    protected $_name = 'Static';
    protected $_primary = 'alias';

    public function getPagesTitles() {
        $select = $this->select()
            ->from($this->_name, array('title', 'alias'))
            ->order('id');
        return $this->getAdapter()->fetchAll($select);
    }

    public function searchByQuery($query, $fieldList) {
        $select = $this->select()
            ->from($this->_name, array('title', 'description' => 'content','alias'))
            ->where("MATCH($fieldList) AGAINST('\"$query\"' in boolean mode)");
        $select->group('id')
            ->order('id DESC');
        return $this->getAdapter()->fetchAll($select);
    }

    public function getPageTitleByAlias($alias) {
        $select = $this->select()
            ->from($this->_name, array('title'))
                       ->where('alias = ? ', $alias);
        return $this->getAdapter()->fetchOne($select);
    }
    public function deletePage($alias, $aliasCannotDelete = ['home', 'about', 'contacts'])
    {
        $where = [
            'alias = ?' => $alias,
            'alias NOT IN (?)' => $aliasCannotDelete
        ];
        $this->delete($where);
    }
}