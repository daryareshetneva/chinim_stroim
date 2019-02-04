<?php
class Shop_Model_Categories{
    private $_select = [];

    public function getAllCategories()
    {
        $table = new Shop_Model_DbTable_Categories();
        $categories =$table->getAllCategories();
        return $this->buildCategoriesTree($categories);
    }

    public function buildCategoriesTree($categories, $parentId = 0) {
        $tree = [];
        foreach ($categories as $key => $category) {
            if ($category['parentId'] == $parentId) {
                $res = $category;
                $res['categories'] = $this->buildCategoriesTree($categories, $category['id']);
                $tree[$category['id']] = $res;
            }
        }
        return $tree;
    }

    public function getArrayCategoriesForAdminPanel($tree, &$link){
        foreach ($tree as $key => $item) {
            $link[$item['id']] = $item['title'];
            if (isset($item['categories']))
            {
                $this->getArrayCategoriesForAdminPanel($item['categories'], $link);
            }
        }
    }

    /*
    Метод рекурсивно добавляет сепаратор к элементам массива через addSeparator
    */
    public function buildSelect($tree)
    {
        foreach ($tree as $key => $item) {
            if (isset($item['categories'])) {
                $tree[$key]['categories'] = $this->buildSelect($this->addSeparator($item['categories']));
            }
        }
        return $tree;
    }

    /* этот метод добавиляет сепаратор
        $items = array();
        return array();
     */
    public function addSeparator($items, $separator = '-')
    {
        foreach ($items as $key => $item) {
            $items[$key]['title'] = $separator . $item['title'];
            if (isset($item['categories'])) {
                $items[$key]['categories'] = $this->addSeparator($item['categories']);
            }
        }
        return $items;
    }


    // вся процедура сортировки
    public function getSortedAllCategories()
    {
        $table = new Shop_Model_DbTable_Categories();
        $allCategories = $table->getAllCategories();

        // сортируем массив по вложенности
        $sortedCategories = $this->buildCategoriesTree($allCategories);
        $withSeparators = $this->buildSelect($sortedCategories);
        // получаем массив [id] => [название]
        $this->getArrayCategoriesForAdminPanel($withSeparators, $this->_select);

        return $this->_select;
    }

    public function getTreeCategoriesById($id)
    {
        $table = new Shop_Model_DbTable_Categories();
        $allCategories = $table->getAllCategories();
        $sortedCategories = $this->buildCategoriesTree($allCategories, $id);
        $this->getArrayCategoriesForAdminPanel($sortedCategories, $this->_select);
        return $this->_select;
    }

    public function getParentIdById($id)
    {
        $table = new Shop_Model_DbTable_Categories();
        $parentId = $table->getCategoryParentIdOnId($id);

        return $parentId;
    }

    public function getCategoryIdByAlias($alias)
    {
        $table = new Shop_Model_DbTable_Categories();
        $categoryId = $table->getCategoryIdByAlias($alias);

        return $categoryId;
    }
}
?>