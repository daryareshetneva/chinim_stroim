<?php class Shop_Model_Orders{

    public function getOrdersByStatus($status){
        if (isset($status))
        {
            $table = new Shop_Model_DbTable_Orders;
            return $table->getOrdersByStatus($status);
        } else {
            return false;
        }
    }

    public function getOrdersOfUser($userId){
        $table = new Shop_Model_DbTable_Orders;

        return $table->getOrdersOfUser($userId);
    }

    public function getOrdersAmountArrayForPeriod($startDate, $endDate) {
        $shopOrdersTable = new Shop_Model_DbTable_Orders();
        $orders = $shopOrdersTable->getNotCanceledOrdersWithGoodsByPeriod($startDate, $endDate);
        $ordersByDate = [];

        foreach ($orders as $order) {
            $oderDate = strtotime(date('Y-m-d 00:00:00', strtotime($order['date'])));
            if (!isset($ordersByDate[$oderDate])) {
                $ordersByDate[$oderDate] = [
                    'amount' => 1,
                    'total' => 0,
                    'ordersIds' => [$order['id']]
                ];
            }
            $ordersByDate[$oderDate]['total'] += $order['price'] * $order['count'];
            if (!in_array($order['id'], $ordersByDate[$oderDate]['ordersIds'])) {
                $ordersByDate[$oderDate]['ordersIds'][] = $order['id'];
                $ordersByDate[$oderDate]['amount']++;
            }
        }

        return $ordersByDate;
    }

    public function getOrderProductsToRepeat($orderId) {
        $orderedProductsTable = new Shop_Model_DbTable_OrderedProducts();
        $products = $orderedProductsTable->getProductsIdsAndAmountsPairsByOrderId($orderId);
        return $products;
    }

    public function getOrderProductsByUserId($userId) {
        $orderedProductsTable = new Shop_Model_DbTable_OrderedProducts();
        $orderedProducts = $orderedProductsTable->getOrderedProductsByUserId($userId);
        $productsByOrderIds = [];
        foreach ($orderedProducts as $orderedProduct) {
            if (!isset($productsByOrderIds[$orderedProduct['idOrder']])) {
                $productsByOrderIds[$orderedProduct['idOrder']] = [];
            }
            $productsByOrderIds[$orderedProduct['idOrder']][] = $orderedProduct;
        }
        return $productsByOrderIds;
    }

}
