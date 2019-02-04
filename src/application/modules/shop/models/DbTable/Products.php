<?php

class Shop_Model_DbTable_Products extends ItRocks_Db_Table_Abstract
{
    protected $_name = 'Shop_Products';
    protected $_primary = 'id';

    public function getProductList()
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from( $this->_name, ['productId' => 'Shop_Products.id', 'productTitle' => 'Shop_Products.title', 'productPrice' => 'Shop_Products.price', 'category' => 'Shop_Categories.title', 'image' => 'Shop_Products.image'])
            ->joinLeft('Shop_Categories', 'Shop_Products.categoryId = Shop_Categories.id')
            ->where('Shop_Products.deleted = ?', 0);
        return $this->getAdapter()->fetchAll( $select );
    }

    public function getAllProducts($filters = [])
    {
        $select = $this->select()->distinct()->group('productId')
            ->setIntegrityCheck(false)
            ->from($this->_name, ['productId' => 'id','title','alias', 'price', 'discount', 'sale', 'image', 'new', 'productOfDay'])
            ->joinLeft(['cats' => 'Shop_Categories'], 'Shop_Products.categoryId = cats.id', ['catTitle' => 'title'])
            ->where('deleted = 0')
            ->group('productId');

        if (!empty($filters)) {
            $select = $this->_addFiltersSelectCondition($select, $filters);
        }

        return $this->getAdapter()->fetchAll($select);
    }

    private function _addFiltersSelectCondition($select, $filters) {
        $counter = 1;
        $innerSelect = $this->select()->setIntegrityCheck(false)->distinct()
            ->from(array('g' => $this->_name), array('id'));

        foreach ($filters as $values) {
            $table = 'filter' . $counter;
            $joinCond = $this->quoteInto(
                '`g`.`id` = `' . $table . '`.`productId` AND ' .
                '`' .$table . '`.`filterElementId` IN (?)',
                $values
            );
            $innerSelect->join(
                array($table => 'Shop_FilterProductsRelations'),
                $joinCond, array()
            );

            $counter++;
        }


        $select->join(
            array('founded' => $innerSelect),
            'founded.id = ' . $this->_name . '.id',
            array())
            ->join(
                ['fg' => 'Shop_FilterProductsRelations'],
                '`' . $this->_name . '`.`id` = `fg`.`productId`',
                []);
        return $select;
    }

    public function getAllProductsForAdmin()
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from( $this->_name, ['productId' => 'Shop_Products.id', 'productTitle' => 'Shop_Products.title', 'productPrice' => 'Shop_Products.price', 'category' => 'Shop_Categories.title'])
            ->joinLeft('Shop_Categories', 'Shop_Products.categoryId = Shop_Categories.id')
            ->where('Shop_Products.deleted = ?', 0);
        return $this->getAdapter()->fetchAll( $select );
    }

    public function getProductById($id)
    {
        $select = $this->select()
            ->from( $this->_name, ['id', 'title', 'description', 'price', 'discount', 'alias', 'categoryId', 'count', 'deleted', 'sale'])
            ->where( 'id = ?', $id );
        return $this->getAdapter()->fetchOne( $select );
    }

    public function getProductTitleById($id)
    {
        $select = $this->select()
            ->from( $this->_name, array('title') )
            ->where( 'alias = ?', $id );
        $id = $this->getAdapter()->fetchOne( $select );
        return $this->find( $id )->current();
    }

    public function moveProductToArchive($id)
    {
        $data = array('deleted' => 1);
        $where = array('id = ?' => $id);
        $this->update($data, $where);
    }


    public function getAllProductsByCategories($categories, $filters)
    {
        $select = $this->select()->distinct()->group('productId')
            ->setIntegrityCheck(false)
            ->from($this->_name, ['productId' => 'id','title','alias', 'price', 'discount', 'sale', 'image', 'new', 'productOfDay'])
            ->joinLeft(['cats' => 'Shop_Categories'], 'Shop_Products.categoryId = cats.id', ['catTitle' => 'title'])
            ->where('categoryId IN (?)', $categories)
            ->where('deleted = 0')
            ->group('productId');

        if (!empty($filters)) {
            $select = $this->_addFiltersSelectCondition($select, $filters);
        }

        return $this->getAdapter()->fetchAll($select);
    }

    public function getSaleProducts() {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(['products' => $this->_name], ['id','title', 'alias', 'price', 'discount', 'sale', 'image', 'new'])
            ->joinLeft(['cats' => 'Shop_Categories'], 'products.categoryId = cats.id', ['catTitle' => 'title'])
            ->where('deleted = 0')
            ->where('sale = 1')
            ->order('title DESC');
        return $this->getAdapter()->fetchAll($select);
    }

    public function getNewProducts() {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(['products' => $this->_name], ['id','title', 'alias', 'price', 'discount', 'sale', 'image', 'new'])
            ->joinLeft(['cats' => 'Shop_Categories'], 'products.categoryId = cats.id', ['catTitle' => 'title'])
            ->where('deleted = 0')
            ->where('new = 1')
            ->order('title DESC');
        return $this->getAdapter()->fetchAll($select);
    }

    public function getByAlias($alias) {
        $select = $this->select()
            ->from($this->_name, ['id'])
            ->where('alias = ?', $alias);
        $id = $this->getAdapter()->fetchOne($select);
        return $this->find($id)->current();
    }

    public function getTitleByAlias($alias) {
        $select = $this->select()
            ->from($this->_name, ['title'])
            ->where('alias = ?', $alias);
        return $this->getAdapter()->fetchOne($select);
    }

    public function getProductsById($products)
    {
        $select = $this->select()
            ->from( $this->_name, ['id', 'title', 'description', 'price', 'discount', 'alias', 'sale', 'discount2', 'image'])
            ->where( 'id IN (?)', $products );
        return $this->getAdapter()->fetchAll( $select );
    }

    public function getProductsByIdForOrder($products)
    {
        $select = $this->select()
            ->from( $this->_name, ['id', 'title', 'price', 'discount'])
            ->where( 'id IN (?)', $products );
        return $this->getAdapter()->fetchAll( $select );
    }

    public function getPaginatorRows ($categoryId, $pageNumber = 1, $itemsPerPage = 10) {
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect(
            $this->select()
                ->from($this->_name, ['id', 'title', 'price', 'categoryId'])
                ->setIntegrityCheck(false)
                ->joinLeft('Shop_Categories', 'Shop_Categories.id = Shop_Products.categoryId', ['categoryTitle' => 'title'])
                ->where('categoryId = ' . $this->getAdapter()->quote($categoryId)))
        );
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($itemsPerPage);
        $paginator->setPageRange(1);
        return $paginator;
    }

    public function searchByQuery($query, $fieldList) {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from($this->_name, array('title', 'description', 'alias', 'price', 'productId' => 'id', 'sale', 'new', 'productOfDay'))
            ->joinLeft('Shop_Categories', 'Shop_Categories.id = Shop_Products.categoryId', ['categoryTitle' => 'title'])
            ->where("MATCH($fieldList) AGAINST('\"$query\"' in boolean mode)")
            ->orWhere('Shop_Products.title LIKE ' . $this->getAdapter()->quote('%' . $query . '%'));
        $select->group('Shop_Products.id')
            ->order('Shop_Products.id DESC');
        return $this->getAdapter()->fetchAll($select);
    }

    public function getAllProductsWithCategories() {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(['Products' => $this->_name], ['id', 'title', 'price', 'categoryId'])
            ->joinLeft(['Categories' => 'Shop_Categories'], 'Categories.id = Products.categoryId', ['catTitle' => 'title', 'parentId']);
        return $this->getAdapter()->fetchAll($select);
    }
}

