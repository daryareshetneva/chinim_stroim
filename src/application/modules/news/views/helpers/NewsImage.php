<?php


class Zend_View_Helper_NewsImage extends Zend_View_Helper_Abstract {

    public function newsImage($imageName) {
        $imageHelper = new News_Model_Images();
        return $imageHelper->url($imageName);
    }

}