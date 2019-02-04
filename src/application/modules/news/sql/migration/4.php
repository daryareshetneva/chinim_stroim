<?php

$_newsModel = new News_Model_DbTable_Table();
$_allNews = $_newsModel->fetchAll();

foreach ($_allNews as $curNews) {
    $curNews['description'] = substr($curNews['content'], 0 , 1000) . '...';
    $curNews->save();
}