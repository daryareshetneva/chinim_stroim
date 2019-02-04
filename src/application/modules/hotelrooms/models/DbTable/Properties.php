<?php

class HotelRooms_Model_DbTable_Properties extends ItRocks_Db_Table_Abstract {

    protected $_name = 'HotelRooms_Properties';
    protected $_primary = 'id';


    public function getByRoomId($roomId) {
        $select = $this->select()
            ->from($this->_name, ['title', 'value', 'id', 'icon'])
            ->where('roomId = ?', $roomId)
            ->order('position');
        return $this->getAdapter()->fetchAll($select);
    }

    public function deleteByRoomId($roomId) {
        $where = [
            'roomId = ?' => $roomId
        ];
        $this->delete($where);
    }
}
