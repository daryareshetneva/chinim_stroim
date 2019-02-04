<?php

class Zend_View_Helper_ServicesMainMenu extends Zend_View_Helper_Abstract {
    
    public function servicesMainMenu($baseUrl) {
        $treeDbModel = new Services_Model_DbTable_Tree();
        $categories = $treeDbModel->getByParent(0);
        $html = '';
        foreach ($categories as $category) {
            $html .= '<li>';
            $html .= '<a href="' . $baseUrl . '/services/' . $category['alias'] . '">' . $category['title'] . '</a>';
            $html .= '</li>';
        }
        return $html;
    }
}