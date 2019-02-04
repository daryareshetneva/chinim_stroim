<?php

class Shop_Model_DbTable_Categories extends ItRocks_Db_Table_Abstract
{
    protected $_name = 'Shop_Categories';
    protected $_primary = 'id';

    public function getAllCategories()
    {
        $select = $this->select()
            ->from( $this->_name, ['id','parentId', 'title','alias'])
            ->order( 'title ASC' );
        return $this->getAdapter()->fetchAll( $select );
    }

    public function getCategoryParentIdOnId($id)
    {
        $select = $this->select()
            ->from( $this->_name, ['parentId'])
            ->where( 'id = ?', $id );
        return $this->getAdapter()->fetchOne( $select );
    }

    public function getCategoryIdByAlias($alias)
    {
        $select = $this->select()
            ->from( $this->_name, ['id', 'title'])
            ->where( 'alias = ?', $alias );
        return $this->getAdapter()->fetchRow( $select );
    }

    public function getTitleByAlias($alias) {
        $select = $this->select()
            ->from($this->_name, ['title'])
            ->where('alias = ?', $alias);
        return $this->getAdapter()->fetchOne($select);
    }

    public function getByAlias($alias) {
        $select = $this->select()
            ->from( $this->_name, ['id'])
            ->where( 'alias = ?', $alias );
        $id = $this->getAdapter()->fetchOne( $select );
        return $this->find($id)->current();
    }
}

