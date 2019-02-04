<?php


class Zend_View_Helper_CertificatesImage extends Zend_View_Helper_Abstract {

    public function certificatesImage($imageName) {
        $imageHelper = new Certificates_Model_Images();
        return $imageHelper->url($imageName);
    }

}