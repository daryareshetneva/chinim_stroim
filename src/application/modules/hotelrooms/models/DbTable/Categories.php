<?php

class HotelRooms_Model_DbTable_Categories extends ItRocks_Db_Table_Abstract {

	protected $_name = 'HotelRooms_Categories';
	protected $_primary = 'id';


	public function getAll() {
		$select = $this->select()
				->from( $this->_name, array('id', 'title', 'alias', 'position') )
				->order( 'position ASC' );
		return $this->getAdapter()->fetchAll( $select );
	}

	public function getMaxPosition() {
	    $select = $this->select()
            ->from($this->_name, ['MAX(position)']);
	    $position = $this->getAdapter()->fetchOne($select);
	    return ($position) ? $position : 0;
    }

    public function getByAlias($alias) {
	    $select = $this->select()
            ->from($this->_name, ['id'])
            ->where('alias = ?', $alias);
	    $id = $select->getAdapter()->fetchOne($select);
	    return $this->find($id)->current();
    }
}
