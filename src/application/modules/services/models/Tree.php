<?php
class Services_Model_Tree {
    protected $_treeDbModel;
    protected $_itemsDbModel;

    public function __construct() {
        $this->_treeDbModel = new Services_Model_DbTable_Tree();
        $this->_itemsDbModel = new Services_Model_DbTable_Items();
    }

    public function getItemsByCategory($id) {
        $subcategories = $this->_treeDbModel->getByParent($id)->fetchAll();

        if ($subcategories)
            return $subcategories;
        else {
            $services = $this->_itemsDbModel->getByCategory($id);
            if ($services)
                return $services;
            else
                return null;
        }
    }
}