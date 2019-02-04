<?php


class Zend_View_Helper_ProductImage extends Zend_View_Helper_Abstract {

    public function productImage($imageName) {
        $imageHelper = new Model_Images_Product();
        return $imageHelper->url($imageName);
    }

}