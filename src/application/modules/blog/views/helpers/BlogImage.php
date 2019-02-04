<?php


class Zend_View_Helper_BlogImage extends Zend_View_Helper_Abstract
{
    public function blogImage($imageName){
        $imageHelper = new Blog_Model_Images();
        return $imageHelper->url($imageName);
    }
}