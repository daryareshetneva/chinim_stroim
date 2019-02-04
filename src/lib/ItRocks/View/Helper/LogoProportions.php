<?php


class Zend_View_Helper_LogoProportions extends Zend_View_Helper_Abstract {

    protected $_maxSmallHeight = 46;

    public function logoProportions($imageName) {
        $imageSize = getimagesize($imageName);

        $smallHeight = $this->_maxSmallHeight;
        $smallWidth = round($this->_maxSmallHeight * $imageSize[0] / $imageSize[1]);


        return [
            'width' => $imageSize[0],
            'height' => $imageSize[1],
            'smallWidth' => $smallWidth,
            'smallHeight' => $smallHeight
        ];

    }

}