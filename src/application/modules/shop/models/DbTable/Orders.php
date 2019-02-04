<?php

class Shop_Model_DbTable_Orders extends ItRocks_Db_Table_Abstract
{
    protected $_name = 'Shop_Orders';
    protected $_primary = 'id';

    public function getOrdersByStatus($status)
    {
        $select = $this->select()
            ->from($this->_name)
            ->where('status = ?', $status)
            ->order('id DESC');
        return $this->getAdapter()->fetchAll($select);
    }

    public function countAllOrders() {
        $select = $this->select()
            ->from($this->_name, array('COUNT(*)'));
        return $this->getAdapter()->fetchOne($select);
    }

    public function countOrdersByStatus($status) {
        $select = $this->select()
            ->from($this->_name, array('COUNT(*)'))
            ->where('status = ?', $status);
        return $this->getAdapter()->fetchOne($select);
    }

    public function getOrdersToday()
    {
        $select = $this->select()
            ->from($this->_name)
            ->order('id DESC');
        return $this->getAdapter()->fetchAll($select);
    }

    public function getOrdersByPeriod($startDate, $endDate) {
        $orderTotalSql = new Zend_Db_Expr('(SELECT SUM(`price` * `count`)   FROM `Shop_Ordered_Products` WHERE `idOrder`=`orders`.`id`)');
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(['orders' => $this->_name], ['id', 'name', 'phone', 'status', 'email', 'date', 'userId', 'total' => $orderTotalSql])
            ->where('orders.date >= ? ', $startDate)
            ->where('orders.date <= ?', $endDate);
        return $this->getAdapter()->fetchAll($select);
    }

    public function updateOrderStatus($id, $status)
    {
        $data = [
          'status' => $status
        ];
        $where = [
            'id = ?' => $id,
        ];
        $this->update($data,$where);
    }

    public function getOrdersOfUser($userId)
    {
        $orderTotalSql = new Zend_Db_Expr('(SELECT SUM(`price` * `count`)   FROM `Shop_Ordered_Products` WHERE `idOrder`=`orders`.`id`)');
        $select = $this->select()
            ->from(['orders' => $this->_name], ['id', 'name', 'phone', 'status', 'email', 'date', 'userId', 'total' => $orderTotalSql])
            ->where('userId = ?', $userId)
            ->order('id DESC');
        return $this->getAdapter()->fetchAll($select);
    }

    public function getNotCanceledOrdersWithGoodsByPeriod($startDate, $endDate) {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(['orders' => $this->_name], ['id', 'date'])
            ->joinLeft(['orderGoods' => 'Shop_Ordered_Products'], 'orderGoods.idOrder = orders.id', ['count', 'price', 'discount'])
            ->where('orders.date >= ? ', $startDate)
            ->where('orders.date <= ?', $endDate)
            ->where('orders.status <> ?', 4);
        return $this->getAdapter()->fetchAll($select);
    }
}