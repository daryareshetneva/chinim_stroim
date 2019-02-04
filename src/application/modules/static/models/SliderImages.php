<?php
class Static_Model_SliderImages {

    public function changePositionById($itemId, $newPosition) {
        $arrayModel = new Model_Array_Abstract();
        $sliderTable = new Static_Model_DbTable_Slider();
        $slider = $sliderTable->find($itemId)->current();
        if (!$slider) {
            throw new Exception('Item not found');
        }
        $swapPosition = $slider->position;

        $positions = $sliderTable->getPositionPairs();
        $itemsWithNewPositions = $arrayModel->swapValue($positions, $swapPosition, $newPosition);
        foreach ($itemsWithNewPositions as $itemId => $position) {
            $sliderTable->update(['position' => $position], ['id = ?' => $itemId]);
        }

    }
}