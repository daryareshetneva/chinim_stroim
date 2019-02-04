<?php

class db_ShopFilterElements {
    const ID = 'id';
    const TITLE = 'title';
    const FILTER_ID = 'filterId';
    const POSITION = 'position';
}

class Shop_Model_DbTable_FilterElements extends ItRocks_Db_Table_Abstract
{
    protected $_name = 'Shop_FilterElements';
    protected $_primary = 'id';

    public function getPaginatorRows ($filterId, $pageNumber = 1, $itemsPerPage = 10) {
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($this->select()->where('filterId = ' . $this->getAdapter()->quote($filterId))));
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($itemsPerPage);
        $paginator->setPageRange(1);
        return $paginator;
    }

    public function deleteByFilterId($filterId) {
        $where = [
            'filterId = ?' => $filterId
        ];
        $this->delete($where);
    }

    public function getElementsPairsByFilterId($filterId) {
        $select = $this->select()
            ->from($this->_name, [db_ShopFilterElements::ID, db_ShopFilterElements::TITLE])
            ->where(db_ShopFilterElements::FILTER_ID . ' = ?', $filterId);

        return $this->getAdapter()->fetchPairs($select);
    }

    public function getFilterWithElementsByProductId($productId) {
        $subQuery = new Zend_Db_Expr('(SELECT filterElementId FROM Shop_FilterProductsRelations WHERE productId = ' . $productId . ')');
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(['fe' => $this->_name], ['elemTitle' => 'title'])
            ->joinLeft(['filters' => 'Shop_Filters'], 'fe.filterId = filters.id', ['catTitle' => 'title'])
            ->where('fe.id IN (' . $subQuery . ')');
        return $this->getAdapter()->fetchPairs($select);

    }
}

