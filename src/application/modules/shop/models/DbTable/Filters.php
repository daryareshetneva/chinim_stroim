<?php

class db_ShopFilters {
    const ID = 'id';
    const TITLE = 'title';
    const ALIAS = 'alias';
    const POSITION = 'position';
}

class Shop_Model_DbTable_Filters extends ItRocks_Db_Table_Abstract
{
    protected $_name = 'Shop_Filters';
    protected $_primary = 'id';

    public function getPairs() {
        $select = $this->select()
            ->from($this->_name, [db_ShopFilters::ID, db_ShopFilters::TITLE])
            ->order(db_ShopFilters::POSITION);
        return $this->getAdapter()->fetchPairs($select);
    }

    public function getAll() {
        $select = $this->select()
            ->from($this->_name, [db_ShopFilters::ID, db_ShopFilters::TITLE, db_ShopFilters::ALIAS, db_ShopFilters::POSITION])
            ->order(db_ShopFilters::POSITION);
        return $this->getAdapter()->fetchAll($select);
    }

    public function getPaginatorRows ($pageNumber = 1, $itemsPerPage = 10) {
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($this->select()));
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($itemsPerPage);
        $paginator->setPageRange(1);
        return $paginator;
    }

    public function getFiltersWithElements() {
        $subqueryForElementIds = new Zend_Db_Expr('(SELECT DISTINCT GROUP_CONCAT(`Shop_FilterElements`.`id`) FROM `Shop_FilterElements` WHERE `Shop_FilterElements`.`filterId` = `filters`.`id`)');
        $subqueryForElementTitles = new Zend_Db_Expr('(SELECT DISTINCT GROUP_CONCAT(`Shop_FilterElements`.`title`) FROM `Shop_FilterElements` WHERE `Shop_FilterElements`.`filterId` = `filters`.`id`)');
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(['filters' => $this->_name], ['id', 'alias', 'title', 'elementIds' => $subqueryForElementIds, 'elementTitles' => $subqueryForElementTitles]);
        return $this->getAdapter()->fetchAll($select);
    }
}

