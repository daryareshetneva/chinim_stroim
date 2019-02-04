<?php
class Tree_Fields {
    const ID = 'id';
    const NAME = 'title';
    const PARENT_ID = 'parent_id';
    const ALIAS = 'alias';
    const POSITION = 'position';
    const LEFTKEY = 'leftkey';
    const RIGHTKEY = 'rightkey';
    const LEVEL = 'level';
    const DESCRIPTION = 'description';
    const META_TITLE = 'metaTitle';
    const META_DESCRIPTION = 'metaDescription';
    const CATEGORY_PHOTO = 'categoryPhoto';
}

class Services_Model_DbTable_Tree extends ItRocks_Db_Table_Abstract {
    protected $_name = 'Services_Tree';
    protected $_primary = Tree_Fields::ID;
    protected $_fields = array(
        Tree_Fields::ID,
        Tree_Fields::ALIAS,
        Tree_Fields::NAME,
        Tree_Fields::PARENT_ID,
        Tree_Fields::POSITION,
        Tree_Fields::LEFTKEY,
        Tree_Fields::RIGHTKEY,
        Tree_Fields::LEVEL,
        Tree_Fields::DESCRIPTION,
        Tree_Fields::META_TITLE,
        Tree_Fields::META_DESCRIPTION,
        Tree_Fields::CATEGORY_PHOTO
    );

    public function getAll() {
        $select = $this->getAdapter()->select()
                ->from($this->_name, $this->_fields)
                ->order('leftkey');

        return $this->getAdapter()->fetchAll($select);
    }

    public function getLimit($limit) {
        $select = $this->getAdapter()->select()->limit($limit)
            ->from($this->_name, $this->_fields);
            //->order('leftkey');

        return $this->getAdapter()->fetchAll($select);
    }

    public function getByParent($parent = 0) {
        $sql = 'SELECT `st1`.`id`, `st1`.`alias`, `st1`.`title`, `st1`.`parent_id`, `st1`.`position`, if(count(`st2`.`id`) > 0, 1, 0) as subcategories, if(count(`si`.`id`) > 0, 1, 0) as services
                FROM `Services_Tree` as `st1`
                  LEFT JOIN `Services_Tree` as `st2`
                    ON `st2`.`parent_id` = `st1`.`id`
                  LEFT JOIN `Services_Items` as `si`
                      on `si`.`category_id` = `st1`.`id`
                WHERE (`st1`.`parent_id` = ' . $this->getAdapter()->quote($parent) . ')
                GROUP BY `st1`.`id`;';

        return $this->getAdapter()->query($sql);
    }

    public function getPairs() {
        $select = $this->getAdapter()->select()
                ->from($this->_name, array(Tree_Fields::ID, Tree_Fields::NAME));

        return $this->getAdapter()->fetchPairs($select);
    }

    public function checkAlias($alias) {
        $sql = 'select if (count(id) > 0, 0, 1) as available
                  from '.$this->_name.'
                WHERE '.$this->_name.'.alias = ' . $this->getAdapter()->quote($alias) . ';';

        $result = $this->getAdapter()->query($sql)->fetch();

        return (bool)$result['available'];
    }

    public function insertToRoot($data) {
        $variable_register_query = 'select @maxRightKey:= if(ISNULL(max('.Tree_Fields::RIGHTKEY.')), 0, max('.Tree_Fields::RIGHTKEY.'))
                                    from '.$this->_name.';';

        $update_query = 'UPDATE '.$this->_name.'
                        SET '.Tree_Fields::RIGHTKEY.' = '.Tree_Fields::RIGHTKEY.' + 2,
                        '.Tree_Fields::LEFTKEY.' = IF('.Tree_Fields::LEFTKEY.' > @maxRightKey + 1, '.Tree_Fields::LEFTKEY.' + 2, '.Tree_Fields::LEFTKEY.')
                        WHERE '.Tree_Fields::RIGHTKEY.' >= @maxRightKey + 1;';

        $insert_query = 'INSERT INTO '.$this->_name.'
                          SET '.Tree_Fields::ALIAS.' = '.$this->getAdapter()->quote($data['alias']).',
                          '.Tree_Fields::NAME.' = '.$this->getAdapter()->quote($data['title']).',
                          '. Tree_Fields::DESCRIPTION . ' = ' . $this->getAdapter()->quote($data['description']) . ',
                          '. Tree_Fields::META_TITLE . ' = ' . $this->getAdapter()->quote($data['metaTitle']) . ',
                          '. Tree_Fields::META_DESCRIPTION . ' = ' . $this->getAdapter()->quote($data['metaDescription']) . ',
                          '. Tree_Fields::CATEGORY_PHOTO . ' = ' . $this->getAdapter()->quote($data['categoryPhoto']) . ',
                          '.Tree_Fields::PARENT_ID.' = '.$this->getAdapter()->quote($data['parent_id']).',                           
                          '.Tree_Fields::POSITION.' = '.$this->getAdapter()->quote($data['position']).',
                          '.Tree_Fields::LEFTKEY.' = @maxRightKey + 1,
                          '.Tree_Fields::RIGHTKEY.' = @maxRightKey + 1 + 1,
                          '.Tree_Fields::LEVEL.' = 0 + 1;';
        $this->getAdapter()->query($variable_register_query);
        $this->getAdapter()->query($update_query);
        $this->getAdapter()->query($insert_query);
    }

    public function insertToParent($data) {
        $variable_register_query = 'select @parentRightKey:='.Tree_Fields::RIGHTKEY.', @parentLevel:='.Tree_Fields::LEVEL.'
                                    from '.$this->_name.'
                                    WHERE id='.$this->getAdapter()->quote($data['parent_id']).';';

        $update_query = 'UPDATE '.$this->_name.'
                        SET '.Tree_Fields::RIGHTKEY.' = '.Tree_Fields::RIGHTKEY.' + 2,
                        '.Tree_Fields::LEFTKEY.' = IF('.Tree_Fields::LEFTKEY.' > @parentRightKey, '.Tree_Fields::LEFTKEY.' + 2, '.Tree_Fields::LEFTKEY.')
                        WHERE '.Tree_Fields::RIGHTKEY.' >= @parentRightKey;';

        $insert_query = 'INSERT INTO '.$this->_name.'
                          SET '.Tree_Fields::ALIAS.' = '.$this->getAdapter()->quote($data['alias']).',
                          '.Tree_Fields::NAME.' = '.$this->getAdapter()->quote($data['title']).',
                          '. Tree_Fields::DESCRIPTION . ' = ' . $this->getAdapter()->quote($data['description']) . ',
                          '. Tree_Fields::META_TITLE . ' = ' . $this->getAdapter()->quote($data['metaTitle']) . ',
                          '. Tree_Fields::META_DESCRIPTION . ' = ' . $this->getAdapter()->quote($data['metaDescription']) . ',
                          '. Tree_Fields::CATEGORY_PHOTO . ' = ' . $this->getAdapter()->quote($data['categoryPhoto']) . ',
                          '.Tree_Fields::PARENT_ID.' = '.$this->getAdapter()->quote($data['parent_id']).',
                          '.Tree_Fields::POSITION.' = '.$this->getAdapter()->quote($data['position']).',
                          '.Tree_Fields::LEFTKEY.' = @parentRightKey,
                          '.Tree_Fields::RIGHTKEY.' = @parentRightKey + 1,
                          '.Tree_Fields::LEVEL.' = @parentLevel + 1;';

        $this->getAdapter()->query($variable_register_query);
        $this->getAdapter()->query($update_query);
        $this->getAdapter()->query($insert_query);
    }

    public function getAllSubItemsById($id) {
        $select = $this->getAdapter()->select()
                ->from(array('st1' => $this->_name), array())
                ->join(array('st2' => $this->_name), 'st2.leftkey >= st1.leftkey and st2.rightkey <= st1.rightkey', array())
                ->join(array('si' => 'Services_Items'), 'si.category_id = st2.id', 'id')
                ->where('st1.id = ?', $id);

        return $this->getAdapter()->fetchAll($select);
    }

    public function deleteNode($id) {
        $variable_register_query = 'select @deletingLeftKey:='.Tree_Fields::LEFTKEY.', @deletingRightKey:='.Tree_Fields::RIGHTKEY.'
                                        from '.$this->_name.'
                                        WHERE id='.$this->getAdapter()->quote($id).';';

        $delete_query = 'DELETE FROM '.$this->_name.' WHERE '.Tree_Fields::LEFTKEY.' >= @deletingLeftKey AND '.Tree_Fields::RIGHTKEY.' <= @deletingRightKey;';

        $update_query = 'UPDATE '.$this->_name.'
                            SET '.Tree_Fields::LEFTKEY.' = IF('.Tree_Fields::LEFTKEY.' > @deletingLeftKey,
                                                             '.Tree_Fields::LEFTKEY.' - (@deletingRightKey - @deletingLeftKey + 1), '.Tree_Fields::LEFTKEY.'),
                              '.Tree_Fields::RIGHTKEY.' = '.Tree_Fields::RIGHTKEY.' - (@deletingRightKey - @deletingLeftKey + 1)
                            WHERE '.Tree_Fields::RIGHTKEY.' > @deletingRightKey;';

        $this->getAdapter()->query($variable_register_query);
        $this->getAdapter()->query($delete_query);
        $this->getAdapter()->query($update_query);
    }

    public function getNodeTitleById($id) {
        $select = $this->select()
                       ->from($this->_name, array('title'))
                       ->where('id = ?', $id);
        return $this->getAdapter()->fetchOne($select);
    }

    public function getNodeTitleByAlias($alias) {
        $select = $this->select()
                       ->from($this->_name, array('title'))
                       ->where('alias = ?', $alias);
        return $this->getAdapter()->fetchOne($select);
    }

    public function getNodeParentIdById($id) {
        $select = $this->select()
                       ->from($this->_name, array('parent_Id'))
                       ->where('id = ?', $id);
        return $this->getAdapter()->fetchOne($select);
    }

    public function getCategoriesByParentId($id) {
        $select = $this->select()
                       ->from($this->_name, array('title', 'id', 'alias', 'metaTitle', 'metaDescription', 'level'))
                       ->where('parent_id = ?', $id);
        return $this->getAdapter()->fetchAll($select);
    }

    public function getCategoriesByAlias($alias) {
        $query = "SELECT `title`, `id`, `alias`, `metaTitle`, `metaDescription`, `level` FROM `Services_Tree` ";
        $query .= "WHERE `parent_id` = (SELECT `id` FROM `Services_Tree` WHERE `alias` = ";
        $query .= $this->getAdapter()->quote($alias) . ");";
        return $this->getAdapter()->query($query);
    }

    public function getCategoryInformationByAlias($alias) {
        $select = $this->select()
                       ->from($this->_name, array('title', 'description', 'metaTitle', 'metaDescription', 'parent_id', 'id', 'alias'))
                       ->where('alias = ?', $alias);
        return $this->getAdapter()->fetchRow($select);
    }

    public function getCategoryInformationById($id) {
        $select = $this->select()
                       ->from($this->_name, array('title', 'description', 'metaTitle', 'metaDescription', 'parent_id', 'id', 'alias'))
                       ->where('id = ?', $id);
        return $this->getAdapter()->fetchRow($select);
    }

}

