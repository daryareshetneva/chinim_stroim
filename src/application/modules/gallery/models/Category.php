<?php

class Gallery_Model_Category {

    protected $_dbTable;

    public function __construct() {
	$this->_dbTable = new Gallery_Model_DbTable_Category();
    }

    public function getAll() {
	return $this->_dbTable->getAll();
    }

    public function getPairsForEdit( $id = null ) {
	if ( $id == null ) {
	    return $this->getPairs();
	}
	$items = $this->getAll();

	// search childs
	/* childs ids */
	$arrayOfChildIds = array( );
	/* counter for arrayWay */
	$curCounter = 0;
	/* search childs ids for find subchilds */
	$arrayWay = array( $curCounter => $id );
	while ( count( $arrayWay ) != 0 ) {

	    /* fix $curCount to $counter */
	    $counter = $curCounter + 1;
	    /* search childs or subchilds */
	    foreach ( $items as $value ) {
		if ( $value[ db_PhotoCategory::_PID ] == $arrayWay[ $curCounter ] ) {
		    $arrayWay[ $counter ] = $value[ db_PhotoCategory::_ID ];
		    $arrayOfChildIds[ ] = $value[ db_PhotoCategory::_ID ];
		    $counter++;
		}
	    }

	    unset( $arrayWay[ $curCounter ] );
	    $curCounter++;
	}

	// edit results
	$result = array( );
	foreach ( $items as $item ) {
	    if ( !in_array( $item[ db_PhotoCategory::_ID ], $arrayOfChildIds ) &&
		    $item[ db_PhotoCategory::_ID ] != $id ) {
		$result[ $item[ db_PhotoCategory::_ID ] ] = $item[ db_PhotoCategory::_TITLE ];
	    }
	}

	return $result;
    }

    public function getPairs( $id = null ) {
	$pairs = $this->_dbTable->getPairs();

	if ( !empty( $pairs ) ) {
	    if ( $id != null ) {
		unset( $pairs[ $id ] );
		if ( count( $pairs ) == 0 ) {
		    return false;
		}
	    }
	    return $pairs;
	}
	return false;
    }

    public function getByParent( $id = 0 ) {
	return $this->_dbTable->getByParent( $id );
    }

    public function getCategory( $id ) {
	if ( $id ) {
	    return $this->_dbTable->getCategory( $id );
	}
	return null;
    }

    public function getChilds( $id ) {
	if ( $id ) {
	    return $this->_dbTable->getChilds( $id );
	}
	return null;
    }

    public function add( $data ) {
	if ( !isset( $data[ db_PhotoCategory::_TITLE ] ) ) {
	    throw new Exception( "Not found requered fields" );
	}
	$data[ db_PhotoCategory::_DATE ] = date( DATE_W3C );
	$this->_dbTable->insert( $data );
    }

    public function edit( $id, $data ) {
	$data[ db_PhotoCategory::_DATE ] = date( DATE_W3C );
	$where = $this->_dbTable->quoteInto( db_PhotoCategory::_ID . " = ?", $id );

	$this->_dbTable->update( $data, $where );
    }

    public function delete( $category ) {
	$childs = $this->getChilds( $category[ db_PhotoCategory::_ID ] );

	if ( !empty( $childs ) ) {
	    $this->_moveChilds( $childs, $category[ db_PhotoCategory::_PID ] );
	}

	$where = $this->_dbTable->quoteInto( db_PhotoCategory::_ID . " = ?", $category[ db_PhotoCategory::_ID ] );
	$this->_dbTable->delete( $where );
    }

    protected function _moveChilds( $childs, $pid ) {
	$data = array(
	    db_PhotoCategory::_PID => $pid
	);
	foreach ( $childs as $child ) {
	    $this->edit( $child[ db_PhotoCategory::_ID ], $data );
	}
    }

}
