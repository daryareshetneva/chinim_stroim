<?php


class Zend_View_Helper_PartnersImage extends Zend_View_Helper_Abstract {

    public function partnersImage($imageName) {
        $imageHelper = new Partners_Model_Images();
        return $imageHelper->url($imageName);
    }

}