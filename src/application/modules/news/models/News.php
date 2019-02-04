<?php
class News_Model_News{
    private function sortNews($what){
        $allNewsByGroup = array();

        foreach ($what as $news) {
            if (!isset($allNewsByGroup[date('M, Y', strtotime($news['date']))])){
                $allNewsByGroup[date('M, Y', strtotime($news['date']))][] = $news;
            } else {
                $allNewsByGroup[date('M, Y', strtotime($news['date']))][] = $news;
            }
        }
        return $allNewsByGroup;
    }

    public function getSortedAllNews(){
        $table          = new News_Model_DbTable_Table();
        $allNews        = $table->getAllNews();

        $sortedAllNews  = $this->sortNews($allNews);
        return $sortedAllNews;
    }

    public function getSortedNewsByTag($tag){
        $table          = new News_Model_DbTable_Table();
        $arrayNewsByTag = $table->getAllByTag($tag);

        $sortedNewsByTag  = $this->sortNews($arrayNewsByTag);
        return $sortedNewsByTag;
    }
}
?>