<?php
class Shop_Model_Products{

    public function getAllProducts($filters = [])
    {
        $productTable = new Shop_Model_DbTable_Products();
        $products = $productTable->getAllProducts($filters);

        return $products;

    }

    public function getProductById($id)
    {
        $productTable = new Shop_Model_DbTable_Products();
        return $productTable->getProductById($id);
    }
    public function getAllProductsForAdmin()
    {
        $productTable = new Shop_Model_DbTable_Products();
        return $productTable->getAllProductsForAdmin();
    }

    public function getProductsByCategories($categories, $filters)
    {
        $categoriesArray = [];
        foreach ($categories as $key => $item)
        {
            $categoriesArray[] = $key;
        }

        $productTable = new Shop_Model_DbTable_Products();
        $products = $productTable->getAllProductsByCategories($categoriesArray, $filters);

        return $products;
    }

    private function convertPrice($products, $amounts)
    {
        $settingsModel = new Model_Settings();
        $discount   = $settingsModel->getSettings(['discountOne', 'discountTwo']);

        // Суммируем общее сумму без скидок
        $totalWithoutDiscount = 0;
        foreach ($products as $key => $product) {
            $totalWithoutDiscount += $product['price'] * $amounts[$product['id']];
        }


        if ($totalWithoutDiscount > $discount['discountTwo']) {
            foreach ($products as $key => $product)
            {
                if ((int)$product['discount2'] !== 0)
                {
                    $saving = (float)$product['price'] / 100 * (float)$product['discount2'];
                    $products[$key]['discount'] = sprintf('%.2f',$product['price'] - $saving);
                }
            }
        } else if ($totalWithoutDiscount > $discount['discountOne']) {

            foreach ($products as $key => $product)
            {
                if ((int)$product['discount'] !== 0)
                {
                    $saving = (float)$product['price'] / 100 * (float)$product['discount'];
                    $products[$key]['discount'] = sprintf('%.2f',$product['price'] - $saving);
                }
            }
        } else {
            foreach ($products as $key => $product)
            {
                $products[$key]['discount'] = 0;

            }
        }
        return $products;
    }

    public function getProductsCart($products)
    {
        $result = [];
        if (!empty($products))
        {
            $productTable = new Shop_Model_DbTable_Products();
            $idProducts = [];
            foreach ($products as $key => $value)
            {
                $idProducts[] = (int)$key;

            }
            $productsDetail = $productTable->getProductsById($idProducts);
            $result = $this->convertPrice($productsDetail, $products);
        }
        return $result;
    }

    public function getProductsForCart($products)
    {
        $result = [];
        if (!empty($products))
        {
            $productTable = new Shop_Model_DbTable_Products();
            $idProducts = [];
            foreach ($products as $key => $value)
            {
                $idProducts[] = (int)$key;
            }
            $result = $productTable->getProductsByIdForOrder($idProducts);

            foreach ($result as $key => $product)
            {
                $result[$key]['count'] = $products[$product['id']];
                $result[$key]['price'] = (float)$product['price'];
            }
        }
        return $result;
    }
}
?>