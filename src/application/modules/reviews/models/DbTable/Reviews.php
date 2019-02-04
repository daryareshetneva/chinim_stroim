<?php

class Reviews_Model_DbTable_Reviews extends ItRocks_Db_Table_Abstract {

	protected $_name = 'Reviews';
	protected $_primary = 'id';

	public function __construct($config = array()) {
//		$this->setPerPage( 5 );
//		$this->setFieldsForSearch( array('id', 'title' => 'userLogin', 'description' => 'shortAnswer', 'answer') );
		parent::__construct( $config );
	}

	public function getAll() {
		$select = $this->select()
				->from( $this->_name, array('id', 'reviewDate', 'title', 'photo', 'answer', 'mark') )
				->order( 'reviewDate DESC' );

		return $this->getAdapter()->fetchAll( $select );
	}

    public function getLimit($limit) {
        $select = $this->select()->limit($limit)
            ->from( $this->_name, array('id', 'reviewDate', 'title', 'photo', 'answer', 'mark') )
            ->order( 'reviewDate DESC' );

        return $this->getAdapter()->fetchAll( $select );
    }

}
