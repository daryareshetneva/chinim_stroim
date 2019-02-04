<?php


class Zend_View_Helper_HotelRoomImage extends Zend_View_Helper_Abstract {

    public function hotelRoomImage($imageName) {
        $imageHelper = new HotelRooms_Model_Images();
        return $imageHelper->url($imageName);
    }

}