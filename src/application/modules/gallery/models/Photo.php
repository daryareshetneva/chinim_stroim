<?php

class Gallery_Model_Photo {

    protected $_dbTable;
    protected $_modelRelations;

    public function __construct() {
	$this->_dbTable = new Gallery_Model_DbTable_Photo();
	$this->_modelRelations = new Gallery_Model_Relations();
    }

    public function getByCategory( $id ) {
	return $this->_dbTable->getPhotosByCategoryId( $id );
    }

    public function getCategory( $id ) {
	if ( $id ) {
	    return $this->_dbTable->getCategory( $id );
	}
	return null;
    }

    public function getPrevAndNextAndCategoryId( $photo ) {
	if ( !$photo ) {
	    return null;
	}
	
	$relation = $this->_modelRelations->getByPhotoId( $photo[ db_Photos::_ID ] );
	$photos = $this->getByCategory( $relation[ db_Photo_Relations::_CATEGORY_ID ] );

	$prev = $next = null;
	$cur = false;

	foreach ( $photos as $item ) {
	    if ( $item[ db_Photos::_ID ] == $photo[ db_Photos::_ID ] ) {
		$cur = true;
		continue;
	    }
	    if ( !$cur ) { 
		$prev = $item;
	    } else {
		$next = $item;
		break;
	    }
	}

	return array($prev, $next, $relation[ db_Photo_Relations::_CATEGORY_ID ]);
    }

    public function add( $data ) {
	if ( !isset( $data[ db_Photos::_SRC ] ) ) {
	    throw new Exception( "Not found requered fields by adding photo" );
	}

	// save category
	$categoryId = $data[ db_Photo_Relations::_CATEGORY_ID ];
	unset( $data[ db_Photo_Relations::_CATEGORY_ID ] );

	// insert photo
	$data[ db_PhotoCategory::_DATE ] = date( DATE_W3C );
	$photoId = $this->_dbTable->insert( $data );

	// insert relation
	$this->_modelRelations->add( $categoryId, $photoId );
    }

    public function edit( $photo, $relation, $data ) {
	$data[ db_PhotoCategory::_DATE ] = date( DATE_W3C );
	$where = $this->_dbTable->quoteInto( db_Photos::_ID . " = ?", $photo[ db_Photos::_ID ] );

	// save category
	$categoryId = $data[ db_Photo_Relations::_CATEGORY_ID ];
	unset( $data[ db_Photo_Relations::_CATEGORY_ID ] );

	// update photo
	$this->_dbTable->update( $data, $where );

	// update relation
	$this->_modelRelations->checkUpdate( $relation, $categoryId );
    }

    public function delete( $photoId, $relation ) {
	if ( empty( $relation ) ) {
	    throw new Exception( "Not found relation" );
	}

	// delete photo
	$where = $this->_dbTable->quoteInto( db_Photos::_ID . " = ?", $photoId );
	$this->_dbTable->delete( $where );

	// delete relation
	$this->_modelRelations->delete( $relation );
    }

    public function deleteArray( $photos ) {
	foreach ( $photos as $photo ) {
	    $relation = $this->_modelRelations->getByPhotoId( $photo[ db_Photos::_ID ] );
	    $this->delete( $photo[ db_Photos::_ID ], $relation );
	}
    }

}
