<?php class Shop_Model_OrderedProducts{

    public function insertOrderedProducts($idOrder, $products){
        $table = new Shop_Model_DbTable_OrderedProducts();
        foreach ($products as $key => $product){
            $table->insert(
                [
                    'id' => null,
                    'idOrder' => $idOrder,
                    'idProduct' => $product['id'],
                    'title' => $product['title'],
                    'count' => $product['count'],
                    'price' => $product['price'],
                    'discount' => $product['discount']
                ]
            );
        }
    }

    public function getOrdersByStatus($status){
        $table = new Shop_Model_DbTable_OrderedProducts();
        return $table->getOrdersByStatus($status);
    }

    public function getOrderById($id){
        $table = new Shop_Model_DbTable_OrderedProducts();
        $orders = $table->getOrderById($id);
        return $this->_sortOrder($orders);
    }

    public function getOrderByIdOfUser($id, $userId){
        $table = new Shop_Model_DbTable_OrderedProducts();
        $orders = $table->getOrderByIdOfUser($id, $userId);
        return $this->_sortOrder($orders);
    }

    public function getOrdersByIdOfUser($userId){
        $table = new Shop_Model_DbTable_OrderedProducts();
        $orders = $table->getOrdersByIdOfUser( $userId);

        return $this->_sortOrder($orders);
    }

    private function _sortOrder($orders)
    {
        if (!empty($orders)){
            $totalPrice = 0;
            $totalCount = 0;
            foreach ($orders as $key => $order){
                //цена со скидкой
                $orders[$key]['discountPrice'] = $order['discount'] > 0 ? ($order['price'] - ((float)$order['price'] * (float)$order['discount'] / (float)100)) * $order['count'] : (float)$order['price']*$order['count'];
                //общие данные по заказу
                $totalCount += (int)$order['count'];
                $totalPrice += (float)$orders[$key]['discountPrice'];
            }
            $orders[0]['totalPrice'] = $totalPrice;
            $orders[0]['totalCount'] = $totalCount;
        }

        return $orders;
    }
}
?>