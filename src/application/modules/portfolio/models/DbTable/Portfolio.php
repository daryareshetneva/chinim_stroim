<?php

class Portfolio_Model_DbTable_Portfolio extends ItRocks_Db_Table_Abstract {
    
    protected $_name = 'Portfolio';
    protected $_primary = 'id';
	
	public function __construct($config = array()) {
		$this->setFieldsForSearch( array('id', 'alias', 'title', 'description') );
		parent::__construct( $config );
	}
   
    public function getAll() {
        $select = $this->select()
                ->from($this->_name, array('id', 'alias', 'title', 'portfolioCatalogId', 'description', 'miniDescription', 'metaTitle', 'metaDescription', 'price', 'date'))
                ->order('id');
       
       return $this->getAdapter()->fetchAll($select);
    }

    public function getLimit($limit) {
        $select = $this->select()->limit($limit)
            ->from($this->_name, array('id', 'alias', 'title', 'portfolioCatalogId', 'description', 'miniDescription', 'metaTitle', 'metaDescription', 'price', 'date'))
            ->order('id');

        return $this->getAdapter()->fetchAll($select);
    }
    
    public function getByCatalogId($catalogId) {
        $select = $this->select()
                ->from($this->_name, array('id', 'title'))
                ->where('portfolioCatalogId = ?', $catalogId);
        return $this->getAdapter()->fetchAll($select);
    }
    
    public function getByAlias($alias) {
        $select = $this->select()
                       ->from($this->_name, array('id', 'alias', 'title', 'description', 'metaTitle', 'metaDescription'))
                       ->where('alias = ?', $alias);
        return $this->getAdapter()->fetchRow($select);
    }
    
    public function getById($id) {
        $select = $this->select()
                ->from($this->_name, array('id', 'title', 'description', 'metaTitle', 'metaDescription', 'price', 'date'))
                ->where('id = ?', $id);
        return $this->getAdapter()->fetchRow($select);
    }
    
// -------------------------------------------search
    public function getAllForSearch() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'title', 'description'))
                ->order('id');
        return $this->getAdapter()->fetchAll($select);
    }
    
}
