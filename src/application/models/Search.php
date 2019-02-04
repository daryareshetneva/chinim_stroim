<?php

class Model_Search {
    private $_modules = [];
    private $_tables = [];

    public function __construct(){
        $this->_modules = Bootstrap::getModuleList();
    }

    public function search($query) {
        if (mb_strlen($query) < 3){
            return [];
        }
        $result = array();
        if ($this->_modules['News']){
            $this->_tables['News_Model_DbTable_Table'] = [
                'type' => 'news',
                'fields' => 'title,content,description',
            ];
        }
        if ($this->_modules['Blog']){
            $this->_tables['Blog_Model_DbTable_Blog'] = [
                'type' => 'blog',
                'fields' => 'title,shortDescription,description'
            ];
        }
        if ($this->_modules['Static']){
            $this->_tables['Static_Model_DbTable_Static'] = [
                'type' => 'static',
                'fields' => 'title,content'
            ];
        }
        if ($this->_modules['Shop']){
            $this->_tables['Shop_Model_DbTable_Products'] = [
                'type' => 'shop',
                'fields' => 'title,description'
            ];
        }
        foreach (array_keys($this->_tables) as $key) {
            if (class_exists($key)) {
                $tableModel = new $key();
                if (method_exists($tableModel, "searchByQuery")){
                    $findResult = $tableModel->searchByQuery(strtolower($query), $this->_tables[$key]['fields']);
                     foreach ($findResult as $item){
                         if (mb_strlen($item['description']) > 200){
                             $item['description'] = strip_tags($item['description']);
                             $item['description'] = substr($item['description'], 0, 200);
                             $item['description'] = rtrim($item['description'], "!,.-");
                             $item['description'] = substr($item['description'], 0, strrpos($item['description'], ' ')) . ' ...';
                         }
                         $result[$key][] = $item;
                     }
                }
            }
        }
        return $result;
    }
  
}
