<?php

class Model_Array_Abstract {

    /**
     * @param array $data Data with id => value
     * @param int $swapValue value to replace
     * @param int $swapToValue value swap
     * @return array
     */
    public function swapValue($data, $swapValue, $swapToValue) {
        $newData = [];
        $operateArray = [];
        if ($swapToValue > $swapValue) {
            foreach ($data as $key => $value) {
                if ($value >= $swapValue && $value <= $swapToValue) {
                    $operateArray[$key] = $value;
                }
            }
            foreach ($operateArray as $key => $value) {
                $newData[$key] = $value - 1;
                if ($value == $swapValue) {
                    $newData[$key] = $swapToValue;
                }
            }
        } else if ($swapToValue < $swapValue) {
            foreach ($data as $key => $value) {
                if ($value <= $swapValue && $value >= $swapToValue) {
                    $operateArray[$key] = $value;
                }
            }
            foreach ($operateArray as $key => $value) {
                $newData[$key] = $value + 1;
                if ($value == $swapValue) {
                    $newData[$key] = $swapToValue;
                }
            }
        }

        foreach ($newData as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

}