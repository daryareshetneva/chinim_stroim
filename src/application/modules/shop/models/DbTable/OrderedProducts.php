<?php

class Shop_Model_DbTable_OrderedProducts extends ItRocks_Db_Table_Abstract
{
    protected $_name = 'Shop_Ordered_Products';
    protected $_primary = 'id';
    protected $orderField = [
        'id' => 'Shop_Ordered_Products.id',
        'status' => 'Shop_Orders.status',
        'name' => 'Shop_Orders.name',
        'phone' => 'Shop_Orders.phone',
        'email' => 'Shop_Orders.email',
        'date' => 'Shop_Orders.date',
        'userId' => 'Shop_Orders.userId',
        'comment' => 'Shop_Orders.comment',
        'productId' => 'Shop_Ordered_Products.idProduct',
        'productTitle' => 'Shop_Ordered_Products.title',
        'count' => 'Shop_Ordered_Products.count',
        'price' => 'Shop_Ordered_Products.price',
        'discount' => 'Shop_Ordered_Products.discount'
    ];

    public function getAllOrdersForAdmin()
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from( $this->_name, ['qq' => 'Shop_Ordered_Products.idOrder', 'idProduct' => 'Shop_Ordered_Products.idProduct', 'title' => 'Shop_Ordered_Products.title', 'count' => 'Shop_Ordered_Products.count', 'price' => 'Shop_Ordered_Products.price', 'discount' => 'Shop_Ordered_Products.discount'])
            ->joinLeft('Shop_Orders', 'Shop_Ordered_Products.idOrder = Shop_Orders.id');
        return $this->getAdapter()->fetchAll( $select );
    }
    public function getOrdersByStatus($status)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from( $this->_name, ['qq' => 'Shop_Ordered_Products.idOrder', 'idProduct' => 'Shop_Ordered_Products.idProduct', 'title' => 'Shop_Ordered_Products.title', 'count' => 'Shop_Ordered_Products.count', 'price' => 'Shop_Ordered_Products.price', 'discount' => 'Shop_Ordered_Products.discount'])
            ->joinLeft('Shop_Orders', 'Shop_Ordered_Products.idOrder = Shop_Orders.id')
            ->where('Shop_Orders.status = ?', $status);
        return $this->getAdapter()->fetchAll( $select );
    }

    public function getOrderById($id)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from( $this->_name, $this->orderField)
            ->joinLeft('Shop_Orders', 'Shop_Ordered_Products.idOrder = Shop_Orders.id')
            ->where('Shop_Orders.id = ?', $id);
        return $this->getAdapter()->fetchAll( $select );
    }
    public function getOrdersByIdOfUser($id)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from( $this->_name, $this->orderField)
            ->joinLeft('Shop_Orders', 'Shop_Ordered_Products.idOrder = Shop_Orders.id')
            ->where('Shop_Orders.id = ?', $id);
        return $this->getAdapter()->fetchAll( $select );
    }

    public function getOrderByIdOfUser($id, $userId)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from( $this->_name, $this->orderField)
            ->joinLeft('Shop_Orders', 'Shop_Ordered_Products.idOrder = Shop_Orders.id')
            ->where('Shop_Orders.id = ?', $id)
            ->where('Shop_Orders.userId = ?', $userId);
        return $this->getAdapter()->fetchAll( $select );
    }

    public function getProductsIdsAndAmountsPairsByOrderId($orderId) {
        $select = $this->select()
            ->from($this->_name, ['idProduct', 'count'])
            ->where('idOrder = ?', $orderId);
        return $this->getAdapter()->fetchPairs($select);
    }

    public function getOrderedProductsByUserId($userId) {
        $innerSql = new Zend_Db_Expr('(SELECT id FROM Shop_Orders WHERE userId = ' . $this->getAdapter()->quote($userId) . ')');
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(['op' => $this->_name], ['orderProductTitle' => 'title', 'idProduct', 'orderProductPrice' => 'price', 'idOrder'])
            ->joinLeft(['products' => 'Shop_Products'], 'products.id = op.idProduct', ['title', 'deleted', 'sale', 'new', 'productOfDay', 'alias', 'productId' => 'id'])
            ->where('op.idOrder IN (' . $innerSql . ')');
        return $this->getAdapter()->fetchAll($select);
    }
}