<?php
class ItRocks_View_Helper_GetCart extends Zend_Controller_Action_Helper_Abstract {
    private $_cart = null;
    private $_price = 0;
    private $_count = 0;

    public function getCart() {
        if (isset($_COOKIE['cart']))
        {
            $this->_cart = json_decode($_COOKIE['cart'], true);
            $productModel = new Shop_Model_Products();
            $products = $productModel->getProductsCart($this->_cart);

            if (isset($products))
            {
                foreach ($this->_cart as $key => $value)
                {
                    $this->_count += $value;
                }

                foreach ($products as $item)
                {
                    $price = $item['price'];
                    if ((int)$item['discount'] > 0){
                        $price = $item['discount'];
                    }
                    $this->_price += $price * (int)$this->_cart[$item['id']];
                }
            }
        }
        return ['price' => $this->_price, 'count' => $this->_count];
    }
}