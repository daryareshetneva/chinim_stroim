<?php
class Items_Fields {
    const ID = 'id';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const CATEGORY_ID = 'category_id';
    const PRICE = 'price';
    const TERM = 'term';
    const POSITION = 'position';
    const ALIAS = 'alias';
    const META_TITLE = 'meta_title';
    const SERVICE_MAIN_PHOTO = 'serviceMainPhoto';
    const META_DESCRIPTION = 'meta_description';
}

class Services_Model_DbTable_Items extends ItRocks_Db_Table_Abstract {
    protected $_name = 'Services_Items';
    protected $_primary = Items_Fields::ID;
    protected $_fields = array(
        Items_Fields::ID,
        Items_Fields::TITLE,
        Items_Fields::DESCRIPTION,
        Items_Fields::CATEGORY_ID,
        Items_Fields::PRICE,
        Items_Fields::TERM,
        Items_Fields::POSITION,
        Items_Fields::ALIAS,
        Items_Fields::META_TITLE,
        Items_Fields::SERVICE_MAIN_PHOTO,
        Items_Fields::META_DESCRIPTION
    );

    public function __construct($config = array()) {
        $this->setFieldsForSearch( array('id', 'title', 'description', 'alias') );
        parent::__construct( $config );
    }

    public function getByCategory($category = 0) {
        $select = $this->getAdapter()->select()
                ->from($this->_name, $this->_fields);
        if ($category)
            $select->where(Items_Fields::CATEGORY_ID . ' = ?', $category);
        return $this->getAdapter()->fetchAll($select);
    }

    public function getByAlias($alias) {
        $select = $this->getAdapter()->select()
                ->from($this->_name, $this->_fields)
                ->join('Services_Tree', 'category_id = Services_Tree.id', array('category_name' => 'title'))
                ->where($this->_name . '.alias = ?', $alias);
        return $this->getAdapter()->fetchRow($select);
    }

    public function getById($id) {
        $select = $this->getAdapter()->select()
            ->from($this->_name, $this->_fields)
            ->join('Services_Tree', 'category_id = Services_Tree.id', array('category_name' => 'title'))
            ->where($this->_name . '.id = ?', $id);
        return $this->getAdapter()->fetchRow($select);
    }

    public function checkAlias($alias) {
        $sql = 'select if (count(id) > 0, 0, 1) as available
                  from Services_Items
                WHERE Services_Items.alias = ' . $this->getAdapter()->quote($alias) . ';';

        $result = $this->getAdapter()->query($sql)->fetch();
        return (bool)$result['available'];
    }

    public function deleteItemsByCategoryIds($ids) {
        if (!empty($ids)) {
            $id_in = ' (';
            foreach ($ids as $id)
                $id_in .= $this->getAdapter()->quote($id['id']) . ',';
            $id_in = substr($id_in, 0, mb_strlen($id_in, 'UTF8') - 1) . ') ';

            $sql = 'delete from ' . $this->_name . ' where ' . Items_Fields::ID . ' in ' . $id_in;
            $this->getAdapter()->query($sql);
        }
    }

    public function getItemsByCategoryAlias($alias) {
        $query = "SELECT `id`, `alias`, `title`, `meta_title`, `meta_description` FROM `Services_Items` ";
        $query .= "WHERE `category_id` = (SELECT `id` FROM `Services_Tree` WHERE `alias` =";
        $query .= $this->getAdapter()->quote($alias) . ");";
        return $this->getAdapter()->query($query);
    }

    public function getItemTitleByAlias($alias) {
        $select = $this->select()
                       ->from($this->_name, array('title'))
                       ->where('alias = ?', $alias);
        return $this->getAdapter()->fetchOne($select);
    }

    public function getItemTitleById($id) {
        $select = $this->select()
                       ->from($this->_name, array('title'))
                       ->where('id = ?', $id);
        return $this->getAdapter()->fetchOne($select);
    }

    public function getItemCategoryIdByAlias($alias) {
        $select = $this->select()
                       ->from($this->_name, array('category_id'))
                       ->where('alias = ?', $alias);
        return $this->getAdapter()->fetchOne($select);
    }
}