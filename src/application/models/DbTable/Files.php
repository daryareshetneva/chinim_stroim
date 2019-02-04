<?php
class FilesFields {
    const ID = 'id';
    const TITLE = 'title';
    const HASH = 'hash';
    const EXT = 'ext';
    const PATH = 'path';
    const DATE = 'date';

    public static $fields = array(
        self::ID,
        self::TITLE,
        self::HASH,
        self::EXT,
        self::PATH,
        self::DATE
    );
}

class Model_DbTable_Files extends ItRocks_Db_Table_Abstract {
    protected $_name = 'Files';
    protected $_primary = 'id';
    protected $_fields = array();

    public function __construct($config = array()) {
        $this->_fields = FilesFields::$fields;
        parent::__construct($config);
    }

    public function checkHash($hash) {
        $select = $this->select()
            ->from($this->_name, $this->_fields)
            ->where('hash = ?', $hash);

        return $this->getAdapter()->fetchRow($select);
    }

    public function getFileInfoByIds(array $ids) {
        $select = $this->getAdapter()->select()
                ->from($this->_name, $this->_fields);

        $file_in = ' (';
        foreach ($ids as $id)
            $file_in .= $this->getAdapter()->quote($id) . ',';
        $file_in = substr($file_in, 0, mb_strlen($file_in, 'UTF8') - 1) . ') ';

        $select->where(FilesFields::ID . ' in ' . $file_in);
        return $this->getAdapter()->fetchAll($select);
    }

    public function deleteFilesByInfo(array $info) {
        $id_in = ' (';
        foreach ($info as $id)
            $id_in .= $this->getAdapter()->quote($id['id']) . ',';
        $id_in = substr($id_in, 0, mb_strlen($id_in, 'UTF8') - 1) . ') ';

        $sql = 'delete from ' . $this->_name . ' where '.FilesFields::ID.' in ' . $id_in;
        $this->getAdapter()->query($sql);
    }
}