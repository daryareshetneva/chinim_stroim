<?php

class Static_Model_DbTable_Feedback extends Zend_Db_Table_Abstract{

    protected $_name = 'Feedback';
    protected $_primary = 'id';

    public function getAll() {
        $select = $this->select()
            ->from($this->_name, array('id', 'name', 'phone', 'message', 'date'))
            ->order('date DESC');
        return $this->getAdapter()->fetchAll($select);
    }

    public function getMessageById($id) {
        $select = $this->select()
            ->from($this->_name)
            ->where('id = ? ', $id)
            ->limit(1);
        return $this->getAdapter()->fetchAll($select);
    }
    public function deleteMessage($id)
    {
        $where = [
            'id = ?' => $id,
        ];
        $this->delete($where);
    }

    public function countFeedback() {
        $select = $this->select()
            ->from($this->_name, array('COUNT(*)'));
        return $this->getAdapter()->fetchOne($select);
    }
}