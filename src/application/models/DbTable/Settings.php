<?php

class Model_DbTable_Settings extends Zend_Db_Table_Abstract{
    
    protected $_name = 'Settings';
    protected $_primary = 'id';
    
    public function getEmailSettings() {
        $select = $this->select()
                ->from($this->_name, array('name', 'value'))
                ->orWhere('name = "mailHost"')
                ->orWhere('name = "mailUsername"')
                ->orWhere('name = "mailPassword"')
                ->orWhere('name = "mailSmtp"' );
        return $this->getAdapter()->fetchPairs($select);
    }
    
    public function saveEmailSettings($emailSettings) {
        $data = array(
            'value' => $emailSettings['mailHost']
        );
        
        $where = "`name` = 'mailHost'";
        $res = $this->update($data, $where);
        
        $data = array(
            'value' => $emailSettings['mailUsername']
        );
        $where = "`name` = 'mailUsername'";
        $this->update($data, $where);
        
        $data = array(
            'value' => $emailSettings['mailPassword']
        );
        $where = "`name` = 'mailPassword'";
        $this->update($data, $where);
        
        $data = array(
            'value' => ($emailSettings['mailSmtp'] == 'on') ? 1 : 0
        );
        $where = "`name` = 'mailSmtp'";
        $this->update($data, $where);
        
    }

    public function getSettings($what = []){
        $select = $this->select()
            ->from($this->_name)
            ->where('name IN (?)',$what);
         return $this->getAdapter()->fetchAll($select);
    }

    public function updateSettings($field, $value){
        $data = array(
            'value'      => $value
        );

        $where = array(
            'name = ?' => $field
        );

        $this->update($data, $where);

    }
}