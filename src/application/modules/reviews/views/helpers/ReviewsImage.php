<?php


class Zend_View_Helper_ReviewsImage extends Zend_View_Helper_Abstract {

    public function reviewsImage($imageName) {
        $imageHelper = new Reviews_Model_Images();
        return $imageHelper->url($imageName);
    }

}