<?php

class db_ShopFilterProductsRelations {
    const ID = 'id';
    const FILTER_ELEMENT_ID = 'filterElementId';
    const PRODUCT_ID = 'productId';
}

class Shop_Model_DbTable_FilterProductsRelations extends ItRocks_Db_Table_Abstract
{
    protected $_name = 'Shop_FilterProductsRelations';
    protected $_primary = 'id';

    public function getFiltersByProductId($productId) {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(['relations' => $this->_name], ['filterElementId', 'productId', 'id'])
            ->joinLeft(['fe' => 'Shop_FilterElements'], 'fe.id = relations.filterElementId', ['elementTitle' => 'title'])
            ->joinLeft(['filters' => 'Shop_Filters'], 'filters.id = fe.filterId', ['filterTitle' => 'title'])
            ->where('productId = ?', $productId);
        return $this->getAdapter()->fetchAll($select);

    }

    public function getProductFilterElementsPairs($productId) {
        $select = $this->select()
            ->from($this->_name, ['filterElementId', 'id'])
            ->where('productId = ?', $productId);
        return $this->getAdapter()->fetchPairs($select);
    }

    public function getProductUsedFilters($productId) {
        $select = $this->select()
                ->setIntegrityCheck(false)
            ->from(['fr' => $this->_name], [])
            ->joinLeft(['fe' => 'Shop_FilterElements'], 'fe.id = fr.filterElementId', ['filterId'])
            ->where('productId = ?', $productId);

        return $this->getAdapter()->fetchCol($select);
    }
}

