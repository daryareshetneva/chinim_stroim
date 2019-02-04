<?php

class db_Photo_Relations {

    // table
    const TABLE = 'Photo_Relations';
    // fields
    const _ID = 'id';
    const _PHOTO_ID = 'photo_id';
    const _CATEGORY_ID = 'category_id';

}

class Gallery_Model_DbTable_Relations extends ItRocks_Db_Table_Abstract {

    protected $_name = db_Photo_Relations::TABLE;
    protected $_primary = db_Photo_Relations::_ID;
    protected $_fields = array( db_Photo_Relations::_ID,
	db_Photo_Relations::_PHOTO_ID, db_Photo_Relations::_CATEGORY_ID );
    
    public function getByPhotoId( $photoId ) {
	$select = $this->select()
		->from( $this->_name, $this->_fields )
		->where( db_Photo_Relations::_PHOTO_ID . " = ?", $photoId );

	return $this->getAdapter()->fetchRow( $select );
    }
}
