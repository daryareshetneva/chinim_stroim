<?php


class Zend_View_Helper_ServicesImage extends Zend_View_Helper_Abstract {

    public function servicesImage($imageName) {
        $imageHelper = new Services_Model_Images();
        return $imageHelper->url($imageName);
    }

}