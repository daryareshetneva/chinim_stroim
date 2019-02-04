<?php

class HotelRooms_Model_DbTable_Rooms extends ItRocks_Db_Table_Abstract {

    protected $_name = 'HotelRooms_Rooms';
    protected $_primary = 'id';


    public function getByCategoryId($categoryId, $limit = '') {
        $select = $this->select()
            ->from( $this->_name, array('id', 'title', 'alias', 'photo', 'pricePerDay', 'pricePerHour',
                'personAmount', 'shortDescription', 'bed', 'shower', 'fridge', 'jacuzzi', 'satTv', 'electrofireplace',
                'sauna', 'wifi', 'shower', 'fireplace', 'conditioner', 'cupboard', 'minibar') )
            ->where('categoryId = ?', $categoryId)
            ->order( 'id ASC' );
        if (!empty($limit)) {
            $select->limit($limit);
        }

        return $this->getAdapter()->fetchAll( $select );
    }

    public function getByAlias($alias) {
        $select = $this->select()
            ->from($this->_name, ['id'])
            ->where('alias = ?', $alias);
        $id = $select->getAdapter()->fetchOne($select);
        return $this->find($id)->current();
    }
}
