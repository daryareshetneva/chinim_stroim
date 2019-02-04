<?php

class Reviews_Model_DbTable_Table extends Zend_Db_Table_Abstract{
    
    protected $_name = 'Reviews';
    protected $_primary = 'id';
    protected $_perPage = 10;
    protected $_allFields = array('id', 'alias', 'review', 'answer', 'metaTitle',
        'metaDescription', 'reviewDate', 'answerDate', 'userLogin', 'adminLogin');


    public function getAdminAll(){
        $select = $this->select()
               ->from($this->_name, array('id', 'alias', 'review', 'answer', 'reviewDate', 'userLogin', 'adminLogin'))
                ->where('answer=""')
               ->order('id DESC');
       
       $result = $this->getAdapter()->fetchAll($select);
       
       return $result;
    }   
    
    public function getAll() {
        $select = $this->select()
                ->from($this->_name, $this->_allFields)
                ->order('reviewDate DESC');
       
        $result = $this->getAdapter()->fetchAll($select);

        return $result;
    }
    
    public function getByAlias($alias) {
        $select = $this->select()
                ->from($this->_name, $this->_allFields)
                ->where('`alias` = ' . $alias);
        
        return $this->getAdapter()->fetchRow($select);
    }
    
    public function searchByQuery($query, $fieldList) {
        $select = $this->select()
                ->from($this->_name, array('id', 'alias', 'title' => 'review', 'description' => 'answer'));
        foreach ($fieldList as $field) {
            $select->orWhere("LOWER(`{$field}`) LIKE {$this->getAdapter()->quote('%' . $query . '%')}");
        }
        $select->group('id')
                ->order('id DESC');
        return $this->getAdapter()->fetchAll($select);
    }
    
    public function getCountBeforeId($id) {
        $select = $this->select()
                ->from($this->_name, array('COUNT(*)'))
                ->where("`id` > {$id}");
        return $this->getAdapter()->fetchOne($select);
    }
    
    public function getCountPerPage() {
        return $this->_perPage;
    }
}