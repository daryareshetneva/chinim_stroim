<?php

class Model_DbTable_Tags extends ItRocks_Db_Table_Abstract{
    
    protected $_name = 'Tags';
    protected $_primary = 'id';
    
    public function getNameById($id) {
        $select = $this->select()
            ->from($this->_name, ['name'])
            ->where('id = ?', $id)
            ->limit(1);
        return $this->getAdapter()->fetchOne($select);
    }

    public function getTagById($id){
        $select = $this->select()
            ->from($this->_name)
            ->where("id = ?", $id);
        return $this->getAdapter()->fetchRow($select);
    }

    public function getTagsByNewsArticle($articleId){
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from("NewsTags")
            ->joinLeft($this->_name, "NewsTags.idTags = Tags.id")
            ->where("NewsTags.idNews = ?", $articleId);
        return $this->getAdapter()->fetchAll($select);
    }

    public function getTagsByBlogArticle($articleId){
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from("BlogTags")
            ->joinLeft($this->_name, "BlogTags.idTags = Tags.id")
            ->where("BlogTags.idBlog = ?", $articleId);
        return $this->getAdapter()->fetchAll($select);
    }

    public function addTag($tag){
        $tagInsert = ["id" => NULL, "name" => $tag];
        return $this->insert($tagInsert);
    }

    public function searchTag($tag)
    {
        $select = $this->select()
            ->from($this->_name)
            ->where("name = ?", $tag);
        return $this->getAdapter()->fetchRow($select);
    }

    public function searchIdbyTagsName($tagNames){
        foreach ($tagNames as $tag => $value){
            $tagNames[$tag] = "'".$value."'";
        }
        $select = $this->select()
            ->from($this->_name, ['id'])
            ->where("name IN (" . implode(',', $tagNames) . ")");
        return $this->getAdapter()->fetchCol($select);
    }

    public function deleteTag($tagId){
        $this->delete("id = '".$tagId."'");
    }

    public function getTagsByPartOfTag($partOfTag){
        $select = $this->select()
            ->from($this->_name)
            ->where("name LIKE %?%", $partOfTag);
        return $this->getAdapter()->fetchAll($select);
    }

    public function getAllTags(){
        $select = $this->select()
            ->from($this->_name, ["name"]);
        return $this->getAdapter()->fetchCol($select);
    }

    public function getAllNewsTagsSortByCount(){
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from("NewsTags", ["name" => "Tags.name", "id" => "Tags.id", "count" => "COUNT(idTags)"])
            ->joinLeft($this->_name, "NewsTags.idTags = Tags.id")
            ->group("idTags")
            ->order("count DESC")
            ->limit(30);
        return $this->getAdapter()->fetchAll($select);
    }

    public function getAllBlogTagsSortByCount()
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from("BlogTags", ["name" => "Tags.name", "id" => "Tags.id", "count" => "COUNT(idTags)"])
            ->joinLeft($this->_name, "BlogTags.idTags = Tags.id")
            ->group("idTags")
            ->order("count DESC")
            ->limit(30);
        return $this->getAdapter()->fetchAll($select);
    }
}