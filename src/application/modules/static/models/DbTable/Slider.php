<?php
class db_Slider {
    const ID = 'id';
    const IMAGE = 'image';
    const POSITION = 'position';
    const URL = 'url';
    const TITLE = 'title';
    const MESSAGE = 'message';
}

class Static_Model_DbTable_Slider extends Zend_Db_Table_Abstract {

    protected $_name = 'Slider';
    protected $_primary = 'id';


    public function getSliderImagesPagination() {
        $select = $this->select()
            ->from($this->_name, [db_Slider::ID, db_Slider::IMAGE, db_Slider::POSITION, db_Slider::URL, db_Slider::TITLE, db_Slider::MESSAGE])
            ->order(db_Slider::POSITION . ' ASC');
        return $select;
    }

    public function getSliderImages() {
        return $this->getAdapter()->fetchAll($this->getSliderImagesPagination());
    }

    public function getMaxPosition() {
        $select = $this->select()
            ->from($this->_name, ['MAX(' . db_Slider::POSITION . ')']);
        return $this->getAdapter()->fetchOne($select);
    }

    public function getPositionPairs() {
        $select = $this->select()
            ->from($this->_name, [db_Slider::ID, db_Slider::POSITION])
            ->order(db_Slider::POSITION);
        return $this->getAdapter()->fetchPairs($select);
    }

}