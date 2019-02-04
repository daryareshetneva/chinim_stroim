<?php

class Services_Model_DbTable_ServicesImages extends Zend_Db_Table_Abstract {
    
  protected $_name = 'ServicesImages';
  protected $_primary = 'id';
   
  public function add($filename, $serviceAlias) {
    $data = array(
      'image' => $filename,
      'serviceAlias' => $serviceAlias
    );

    $this->insert($data);
  }

  public function getImagesByServiceAlias($serviceAlias) {
    $select = $this->select()
		   ->from($this->_name, array('image', 'id'))
	           ->where('serviceAlias = ?', $serviceAlias);
    return $this->getAdapter()->fetchAll($select);
  }

    public function deleteImagesByServiceAlias($serviceAlias) {
      $whereCondition = 'serviceAlias = "' . $serviceAlias . '"';
      $this->delete($whereCondition);
    }

    public function getAll() {
        $select = $this->select()
            ->from( $this->_name, array('image', 'id', 'serviceAlias'))
            ->order( 'id DESC' );

        return $this->getAdapter()->fetchAll( $select );
    }

}
