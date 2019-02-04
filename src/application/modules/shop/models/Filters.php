<?php
class Shop_Model_Filters {

    public function getAvailableFiltersWithElements() {
        $result = [];
        $filtersTable = new Shop_Model_DbTable_Filters();
        $filtersWidthElements = $filtersTable->getFiltersWithElements();

        foreach ($filtersWidthElements as $item) {
            $elements = [];
            $elementIds = explode(',', $item['elementIds']);
            $elementTitles = explode(',', $item['elementTitles']);
            foreach ($elementIds as $key => $elementId) {
                $elements[$elementId] = $elementTitles[$key];
            }
            $result[$item['id']] = [
                'alias' => $item['alias'],
                'title' => $item['title'],
                'elements' => $elements
            ];
        }

        return $result;
    }

}
?>