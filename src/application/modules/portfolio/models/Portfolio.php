<?php

class Portfolio_Model_Portfolio {
    
    public function getPortfoliosWithCommentsByCatalogId($catalogId) {
        $workTable = new Portfolio_Model_DbTable_Portfolio;
        $works = $workTable->getByCatalogId($catalogId);
        
        
        $workCommentsTable = new Portfolio_Model_DbTable_PortfolioComments;
        $comments = $workCommentsTable->getPairs();
        
        foreach ($works as $key => $work) {
            $works[$key]['comments'] = 0;
            foreach ($comments as $commentId => $commentWorkId) {
                if ($commentWorkId == $work['id']) {
                    $works[$key]['comments']++;
                }
            }
        }
        
        return $works;
    }
    
    public function getAll() {
        $portfolioCatalogsTable = new Portfolio_Model_DbTable_PortfolioCatalogs;
        $portfolioTable = new Portfolio_Model_DbTable_Portfolio;
        $portfolioImagesTable = new Portfolio_Model_DbTable_PortfolioImages;

        $catalogs = $portfolioCatalogsTable->getPairs();
        $portfolios = $portfolioTable->getAll();

        $items = array();

        foreach ($catalogs as $catalogId => $catalogTitle) {
            $catalogItems = array();
            foreach ($portfolios as $portfolio) {	
                if ($portfolio['portfolioCatalogId'] == $catalogId) {
                    $images = $portfolioImagesTable->getImagesByPortfolioId($portfolio['id']);
                    $catalogItems[] = array(
                        'id' => $portfolio['id'],
                        'title' => $portfolio['title'],
                        'alias' => $portfolio['alias'],
                        'metaTitle' => $portfolio['metaTitle'],
                        'metaDescription' => $portfolio['metaDescription'],
                        'description' => $portfolio['description'],
                        'images' => $images
                    );
                }
            }
            $items[$catalogId] = array(
                'id' => $catalogId,
                'title' => $catalogTitle,
                'items' => $catalogItems
            );
        }

        return $items;
    }
    public function getPortfolioByAlias($alias) {
        $table = new Portfolio_Model_DbTable_Portfolio;
        $tableImages = new Portfolio_Model_DbTable_PortfolioImages;
        
        $item = $table->getByAlias($alias);
        $itemImages = $tableImages->getImagesByPortfolioId($item['id']);

        $item['images'] = $itemImages;
        
        return $item;
    }
    public function getPortfolioById($id) {
        $table = new Portfolio_Model_DbTable_Portfolio;
        $tableImages = new Portfolio_Model_DbTable_PortfolioImages;
        
        $item = $table->getById($id);
        $itemImages = $tableImages->getImagesByPortfolioId($id);
        
        $item['images'] = $itemImages;
        
        return $item;
    }
}
