<?php

class Blog_Model_Blog extends Zend_Db_Table_Abstract {
    
    public function getAllWithComments() {
        $diyTable = new Blog_Model_DbTable_Blog;
        $diys = $diyTable->getAll();
        
        $diyCommentsTable = new Blog_Model_DbTable_BlogComments;
        $comments = $diyCommentsTable->getPairs();

        foreach ($diys as $key => $diy) {
            $diys[$key]['comments'] = 0;
            foreach ($comments as $commentId => $commentDiyId) {
                if ($commentDiyId == $diy['id']) {
                    $diys[$key]['comments']++;
                }
            }
        }
        
        return $diys;
    }
    
    public function getAll() {
        $diyTable = new Blog_Model_DbTable_Blog;
        return $diyTable->getAll();
    }
}
