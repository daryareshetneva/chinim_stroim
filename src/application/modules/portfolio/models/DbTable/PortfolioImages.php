<?php

class Portfolio_Model_DbTable_PortfolioImages extends Zend_Db_Table_Abstract {
    
  protected $_name = 'PortfolioImages';
  protected $_primary = 'id';
   
  public function add($filename, $portfolioId) {
    $data = array(
      'image' => $filename,
      'portfolioId' => $portfolioId
    );

    $this->insert($data);
  }

  public function getImagesByPortfolioId($portfolioId) {
    $select = $this->select()
		   ->from($this->_name, array('image', 'id', 'portfolioId'))
	           ->where('portfolioId = ?', $portfolioId);
    return $this->getAdapter()->fetchAll($select);
  }

    public function getMainImageOfPortfolioById($portfolioId) {
        $select = $this->select()->limit(1)
            ->from($this->_name, array('image', 'id', 'portfolioId'))
            ->where('portfolioId = ?', $portfolioId);

        return $this->getAdapter()->fetchAll($select);
    }

    public function getAll() {
        $select = $this->select()
            ->from( $this->_name, array('image', 'id', 'portfolioId'))
            ->order( 'id DESC' );

        return $this->getAdapter()->fetchAll( $select );
    }

}
