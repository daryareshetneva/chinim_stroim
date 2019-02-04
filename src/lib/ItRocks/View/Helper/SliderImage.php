<?php


class Zend_View_Helper_SliderImage extends Zend_View_Helper_Abstract {

    public function sliderImage($imageName) {
        $imageHelper = new Model_Images_Slider();
        return $imageHelper->url($imageName);
    }

}