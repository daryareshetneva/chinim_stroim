<?php

class Gallery_Model_Relations {

    protected $_dbTable;

    public function __construct() {
	$this->_dbTable = new Gallery_Model_DbTable_Relations();
    }
    
    public function getByPhotoId( $id ) {
	if ( $id ) {
	    return $this->_dbTable->getByPhotoId( $id );
	}
	return null;
    }
    
    public function add( $categoryId, $photoId ) {
	if ( $categoryId == null || $photoId == null ) {
	    throw new Exception( "Not found requered fields by adding relation" );
	}

	$data = array(
	    db_Photo_Relations::_CATEGORY_ID => $categoryId,
	    db_Photo_Relations::_PHOTO_ID => $photoId
	);

	$this->_dbTable->insert( $data );
    }
    
    public function checkUpdate( $relation, $categoryId ) {
	if ( $relation[ db_Photo_Relations::_CATEGORY_ID ] == $categoryId ) {
	    return;
	}
	
	$where = $this->_dbTable->quoteInto( db_Photo_Relations::_ID . " = ?", $relation[ db_Photo_Relations::_ID ] );

	$data = array(
	    db_Photo_Relations::_CATEGORY_ID => $categoryId,
	    db_Photo_Relations::_PHOTO_ID => $relation[ db_Photo_Relations::_PHOTO_ID ]
	);

	$this->_dbTable->update( $data, $where );
    }

    public function delete( $relation ) {
	$where = $this->_dbTable->quoteInto( db_Photo_Relations::_ID . " = ?", $relation[ db_Photo_Relations::_ID ] );
	$this->_dbTable->delete( $where );
    }
    
    public function changeCategory( $oldCategoryId, $categoryId ) {	
	$where = $this->_dbTable->quoteInto( db_Photo_Relations::_CATEGORY_ID . " = ?", $oldCategoryId );

	$data = array(
	    db_Photo_Relations::_CATEGORY_ID => $categoryId
	);

	$this->_dbTable->update( $data, $where );
    }
}
