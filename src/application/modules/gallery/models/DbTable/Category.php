<?php

class db_PhotoCategory {
    // table

    const TABLE = 'Photo_Categories';
    // fields
    const _ID = 'id';
    const _PID = 'pid';
    const _TITLE = 'title';
    const _DESC = 'desc';
    const _ALIAS = 'alias';
    const _METATITLE = 'metaTitle';
    const _METADESC = 'metaDesc';
    const _DATE = 'date';
    const _IMAGE = 'image';

}

class Gallery_Model_DbTable_Category extends ItRocks_Db_Table_Abstract {

    protected $_name = db_PhotoCategory::TABLE;
    protected $_primary = db_PhotoCategory::_ID;
    protected $_fields = array( db_PhotoCategory::_ID, db_PhotoCategory::_PID, db_PhotoCategory::_TITLE,
	db_PhotoCategory::_DESC, db_PhotoCategory::_ALIAS, db_PhotoCategory::_METATITLE,
	db_PhotoCategory::_METADESC, db_PhotoCategory::_DATE, db_PhotoCategory::_IMAGE );

    public function getPairs() {
        $select = $this->select()
            ->from( $this->_name, array( db_PhotoCategory::_ID, db_PhotoCategory::_TITLE ) )
            ->order( db_PhotoCategory::_TITLE );
        return $this->getAdapter()->fetchPairs( $select );
    }

    public function getAll() {
        $select = $this->select()
            ->from( $this->_name, $this->_fields )
            ->order( db_PhotoCategory::_TITLE );
        return $this->getAdapter()->fetchAll( $select );
    }

    public function getByParent( $pid ) {
        $catId = db_PhotoCategory::_ID;
        $catPid = db_PhotoCategory::_PID;
        $relCatId = db_Photo_Relations::_CATEGORY_ID;
        $select = $this->select()
            ->setIntegrityCheck( false )
            ->from( $this->_name, $this->_fields )
            ->joinLeft( array( 'childs' => $this->_name ), "{$this->_name}.`{$catId}` = `childs`.`{$catPid}`", array( "childs" => db_PhotoCategory::_ID ) )
            ->joinLeft( array( 'relations' => db_Photo_Relations::TABLE ), "{$this->_name}.`{$catId}` = `relations`.`{$relCatId}`", array( "photos" => db_Photo_Relations::_ID ) )
            ->where( "`{$this->_name}`.`{$catPid}` = ?", $pid )
            ->group( "{$this->_name}.{$catId}" )
            ->order( db_PhotoCategory::_TITLE );
        return $this->getAdapter()->fetchAll( $select );
    }

    public function getCategory( $id ) {
        $select = $this->select()
            ->from( $this->_name, $this->_fields )
            ->where( db_PhotoCategory::_ID . " = ?", $id );

        return $this->getAdapter()->fetchRow( $select );
    }

    public function getChilds( $id ) {
        $select = $this->select()
            ->from( $this->_name, $this->_fields )
            ->where( db_PhotoCategory::_PID . " = ?", $id );

        return $this->getAdapter()->fetchAll( $select );
    }

    public function getCategoryTitleById($id){
        $select = $this->select()
            ->from( $this->_name, db_PhotoCategory::_TITLE)
            ->where( db_PhotoCategory::_ID . " = ?", $id );
        return $this->getAdapter()->fetchOne($select);
    }

}
