<?php

class HotelRooms_Model_DbTable_RoomPhotos extends ItRocks_Db_Table_Abstract {

	protected $_name = 'HotelRooms_Photos';
	protected $_primary = 'id';


	public function getByItemId($itemId) {
		$select = $this->select()
				->from( $this->_name, array('id','photo') )
                ->where('roomId = ?', $itemId)
				->order( 'position ASC' );
		return $this->getAdapter()->fetchAll( $select );
	}

	public function deleteByRoomId($roomId) {
	    $where = [
	        'roomId = ?' => $roomId
        ];
	    $this->delete($where);
    }

    public function getMaxPosition() {
        $select = $this->select()
            ->from($this->_name, ['MAX(position)']);
        $position = $this->getAdapter()->fetchOne($select);
        return ($position) ? $position : 0;
    }

}
