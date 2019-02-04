<?php


class Zend_View_Helper_CommonImage extends Zend_View_Helper_Abstract {

    public function commonImage($imageName) {
        $imageHelper = new Model_Images_Common();
        return $imageHelper->url($imageName);
    }

}