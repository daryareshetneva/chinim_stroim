<?php


class Zend_View_Helper_SpecialistImage extends Zend_View_Helper_Abstract {

    public function specialistImage($imageName) {
        $imageHelper = new Specialists_Model_Images();
        return $imageHelper->url($imageName);
    }

}