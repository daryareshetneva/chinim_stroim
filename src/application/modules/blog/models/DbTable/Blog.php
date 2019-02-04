<?php

class Blog_Model_DbTable_Blog extends ItRocks_Db_Table_Abstract {

	protected $_name = 'Blog';
	protected $_primary = 'id';

	public function __construct($config = array()) {
		$this->setFieldsForSearch( array('id', 'alias', 'title', 'description' => 'shortDescription') );
		parent::__construct( $config );
	}

	public function getAll() {
		$select = $this->select()->setIntegrityCheck(false)
				->from( $this->_name,
						array('id', 'title', 'shortDescription', 'image', 'date', 'alias', 'metaTitle', 'metaDescription') )
                ->group('id')
				->order( 'Blog.date DESC' );
        $articles = $this->getAdapter()->fetchAll( $select );

        return $articles;
	}

    public function getAllByTag($tag)
    {
        $tableTags = new Model_DbTable_Tags();
        $select = $this->select()->setIntegrityCheck(false)
            ->from( $this->_name,
                array('id', 'title', 'shortDescription', 'image', 'date', 'alias', 'metaTitle', 'metaDescription',
                    'comment_count' => 'count(BlogComments.diyId)')
            )
            ->joinLeft('BlogComments', 'Blog.id = BlogComments.diyId AND BlogComments.status = 1', array())
            ->joinLeft('BlogTags', 'BlogTags.idBlog = Blog.id')
            ->where('BlogTags.idTags = ?', $tag)
            ->group('id')
            ->order( 'Blog.date DESC' );
        $articles = $this->getAdapter()->fetchAll( $select );
        foreach($articles as $key => $article)
        {
            $articles[$key]['tags'] = $tableTags->getTagsByBlogArticle($article['id']);
        }
        return $articles;
    }

	public function getByAlias($alias) {
		$select = $this->select()
				->from( $this->_name, array('id') )
				->where( 'alias = ?', $alias );
		$id = $this->getAdapter()->fetchOne( $select );
		return $this->find( $id )->current();
	}

	public function getAllForSearch() {
		$select = $this->select()
				->from( $this->_name, array('id', 'title', 'shortDescription', 'description', 'alias') )
				->order( 'date DESC' );
		return $this->getAdapter()->fetchAll( $select );
	}

	public function getTitleByAlias($alias) {
        $select = $this->select()
            ->from($this->_name, ['title'])
            ->where('alias = ?', $alias);
        return $this->getAdapter()->fetchOne($select);
    }

    public function searchByQuery($query, $fieldList) {
        $select = $this->select()
            ->from($this->_name, array('title', 'shortDescription', 'description', 'alias'))
            ->where("MATCH($fieldList) AGAINST('\"$query\"' in boolean mode)");
        $select->group('id')
            ->order('id DESC');
        return $this->getAdapter()->fetchAll($select);
    }

    public function getCountBeforeId($id) {
        $select = $this->select()
            ->from($this->_name, array('COUNT(*)'))
            ->where("`id` > {$id}");
        return $this->getAdapter()->fetchOne($select);
    }

    public function getCountPerPage() {
        return 10;
    }
}
