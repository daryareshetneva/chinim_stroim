<?php

class News_Model_DbTable_Table extends Zend_Db_Table_Abstract{
    
    protected $_name = 'News';
    protected $_primary = 'id';
    
    public function getPages($page, $limit){
        $info = array();
        
        $select = $this->select()
                ->from($this->_name, array('id', 'title', 'content', 'description', 'date', 'alias', 'metaTitle', 'metaDescription', 'image'))
                ->limitPage($page, $limit)
                ->order('id DESC');
       
       $result = $this->getAdapter()->fetchAll($select);
       
       foreach($result as $row) {
           $row['date'] = date('d.m.Y', strtotime($row['date']));
           $info[] = $row;
       }
       
       return $info;
    }
    
    public function addImportNews($news, $short) {
        $data = array(
            'title' => 'Поступление товара',
            'content' => $news,
            'description' => $short,
            'date' => date('Y-m-d H:i:s'),
            'userId' => 1
        );
        $this->insert($data);
    }
    
    public function getNews($nId) {
        $select = $this->select()
                ->from($this->_name, array('id', 'title', 'content', 'description', 'date', 'alias', 'metaTitle', 'metaDescription'))
                ->where('id = ?', $nId);
        return $this->getAdapter()->fetchRow($select);
    }

    public function getLastTenNews($limit) {
        $select = $this->select()
                       ->from($this->_name, array('id', 'title', 'description', 'date', 'alias', 'metaTitle', 'metaDescription'))
                        ->limit($limit)
                       ->order('date DESC');
        return $this->getAdapter()->fetchAll($select);
    }

    public function getLastNews($limit = 3) {
        $select = $this->select()
            ->from($this->_name, array('id', 'title', 'content', 'date', 'alias', 'image', 'description'))
            ->limit($limit)
            ->order('date DESC');
        return $this->getAdapter()->fetchAll($select);
    }

    public function getByAlias($alias) {
        $select = $this->select()
            ->from( $this->_name, array('id') )
            ->where( 'alias = ?', $alias );
        $id = $this->getAdapter()->fetchOne( $select );
        if ($id) {
            return $this->find($id)->current();
        }
        return null;
    }

    public function getAllNews(){
        $select = $this->select()
            ->from($this->_name)
            ->order('date DESC');
        return $this->getAdapter()->fetchAll($select);
    }

    public function getTitleByAlias($alias) {
        $select = $this->select()
            ->from($this->_name, ['title'])
            ->where('alias = ?', $alias);
        return $this->getAdapter()->fetchOne($select);
    }

    public function getAllByTag($tag)
    {
        $tableTags = new Model_DbTable_Tags();
        $select = $this->select()->setIntegrityCheck(false)
            ->from( $this->_name)
            ->joinLeft('NewsTags', 'NewsTags.idNews = News.id')
            ->where('NewsTags.idTags = ?', $tag)
            ->group('id')
            ->order( 'News.date DESC' );
        $articles = $this->getAdapter()->fetchAll( $select );
        foreach($articles as $key => $article)
        {
            $articles[$key]['tags'] = $tableTags->getTagsByNewsArticle($article['id']);
        }
        return $articles;
    }

    public function searchByQuery($query, $fieldList) {
        $select = $this->select()
            ->from($this->_name, array('title', 'description', 'alias'))
            ->where("MATCH($fieldList) AGAINST('\"$query\"' in boolean mode)");
        $select->group('id')
            ->order('id DESC');
        $result = $this->getAdapter()->fetchAll($select);
        return $result;
    }

}