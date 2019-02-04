<?php
class FileRelationsFields {
    const ID = 'id';
    const TITLE = 'title';
    const FILE_ID = 'file_id';
    const MODULE_ID = 'module_id';
    const ACTION_ID = 'action_id';
    const POST_ID = 'post_id';

    public static $fields = array(
        self::ID,
        self::TITLE,
        self::FILE_ID,
        self::MODULE_ID,
        self::ACTION_ID,
        self::POST_ID
    );
}

class Model_DbTable_FileRelations extends ItRocks_Db_Table_Abstract {
    protected $_name = 'FileRelations';
    protected $_primary = 'id';
    protected $_fields = array();

    public function __construct($config = array()) {
        $this->_fields = FileRelationsFields::$fields;
        parent::__construct($config);
    }

    public function countFileLinks($relation_id) {
        $select = $this->select()
            ->from(array('FR1' => $this->_name), array('count' => 'count(FR2.id)', 'file_id' => 'FR1.file_id'))
            ->join(array('FR2' => $this->_name), 'FR1.file_id = FR2.file_id', array())
            ->where('FR1.id = ?', $relation_id);
        return $this->getAdapter()->fetchRow($select);
    }

    public function getFiles($module_id, $action_id, $post_id) {
        $select = $this->select()->setIntegrityCheck(false)
            ->from($this->_name, array(FileRelationsFields::ID, FileRelationsFields::FILE_ID, FileRelationsFields::TITLE))
            ->join('Files', 'file_id = Files.id', array('Files.date', 'Files.path'))
            ->where('module_id = ' . $this->getAdapter()->quote($module_id) . ' AND action_id = ' . $this->getAdapter()->quote($action_id) . ' AND post_id = ' . $this->getAdapter()->quote($post_id));
        return $this->getAdapter()->fetchAll($select);
    }

    public function getFileStatusToDeleteByMAP($module_id, $action_id, array $post_ids) {
        $post_in = ' (';
        foreach ($post_ids as $id)
            $post_in .= $this->getAdapter()->quote($id['id']) . ',';
        $post_in = substr($post_in, 0, mb_strlen($post_in, 'UTF8') - 1) . ') ';

        $module_id = $this->getAdapter()->quote($module_id);
        $action_id = $this->getAdapter()->quote($action_id);

        $sql = 'select '.FileRelationsFields::FILE_ID.', if('.FileRelationsFields::FILE_ID.' in (select '.FileRelationsFields::FILE_ID.'
                               from '.$this->_name.'
                               WHERE '.FileRelationsFields::ID.' not in (select '.FileRelationsFields::ID.'
                                                from '.$this->_name.'
                                                WHERE '.FileRelationsFields::MODULE_ID.' = '.$module_id.' and '.FileRelationsFields::ACTION_ID.' = '.$action_id.' and '.FileRelationsFields::POST_ID.' in '.$post_in.')
                                     AND '.FileRelationsFields::FILE_ID.' in (select '.FileRelationsFields::FILE_ID.'
                                                     from '.$this->_name.'
                                                     WHERE '.FileRelationsFields::MODULE_ID.' = '.$module_id.' and '.FileRelationsFields::ACTION_ID.' = '.$action_id.' and '.FileRelationsFields::POST_ID.' in '.$post_in.')), 0, 1) as to_delete
                from '.$this->_name.'
                WHERE '.FileRelationsFields::MODULE_ID.' = '.$module_id.' and '.FileRelationsFields::ACTION_ID.' = '.$action_id.' AND '.FileRelationsFields::POST_ID.' in '.$post_in.'
                GROUP BY '.FileRelationsFields::FILE_ID.';';

        return $this->getAdapter()->query($sql)->fetchAll();
    }

    public function deleteRelationsByMAP($module_id, $action_id, array $post_ids) {
        $post_in = ' (';
        foreach ($post_ids as $id)
            $post_in .= $this->getAdapter()->quote($id['id']) . ',';
        $post_in = substr($post_in, 0, mb_strlen($post_in, 'UTF8') - 1) . ') ';

        $module_id = $this->getAdapter()->quote($module_id);
        $action_id = $this->getAdapter()->quote($action_id);

        $this->getAdapter()->delete($this->_name, 'module_id = ' . $module_id . ' and action_id = ' . $action_id . ' and post_id in ' . $post_in);
    }
}