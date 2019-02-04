<?php

class db_Photos {

    // table
    const TABLE = 'Photos';
    // fields
    const _ID = 'id';
    const _SRC = 'src';
    const _CUTSRC = 'cutSrc';
    const _TITLE = 'title';
    const _DESC = 'desc';
    const _DATE = 'date';

}

class Gallery_Model_DbTable_Photo extends ItRocks_Db_Table_Abstract {

    protected $_name = db_Photos::TABLE;
    protected $_primary = db_Photos::_ID;
    protected $_fields = array( db_Photos::_ID, db_Photos::_SRC, db_Photos::_CUTSRC, db_Photos::_TITLE, db_Photos::_DESC, db_Photos::_DATE );

    public function getPhotosByCategoryId( $id ) {
	$relTable = db_Photo_Relations::TABLE;
	$relCatId = db_Photo_Relations::_CATEGORY_ID;
	$relPhotoId = db_Photo_Relations::_PHOTO_ID;
	$photoId = db_Photos::_ID;
	$photoDate = db_Photos::_DATE;

	$joinWhere = $this->getAdapter()->quoteInto("{$relTable}.`{$relCatId}` = ?", $id);
	
	$select = $this->select()
		->from( $this->_name, $this->_fields )
		->join( $relTable, $joinWhere . " AND `{$this->_name}`.`{$photoId}` = `{$relTable}`.`{$relPhotoId}`", array() )
		->order( "{$this->_name}.{$photoDate} DESC" );
		
	return $this->getAdapter()->fetchAll( $select );
    }
    
    public function getCategory( $id ) {
	$select = $this->select()
		->from( $this->_name, $this->_fields )
		->where( db_Photos::_ID . " = ?", $id );

	return $this->getAdapter()->fetchRow( $select );
    }
}
