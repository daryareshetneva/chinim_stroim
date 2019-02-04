<?php

class Shop_Model_DbTable_ProductImages extends ItRocks_Db_Table_Abstract
{
    protected $_name = 'Shop_ProductImages';
    protected $_primary = 'id';

    public function getImagesByProductId($productId) {
        $select = $this->select()
            ->from($this->_name, ['id', 'image'])
            ->where('productId = ?', $productId)
            ->order('position');
        return $this->getAdapter()->fetchAll($select);
    }
}

