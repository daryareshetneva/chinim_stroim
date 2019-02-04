<?php

$catalogsTable = new Portfolio_Model_DbTable_PortfolioCatalogs();
$catalogs = $catalogsTable->getAll();

$catalogIds = array();
foreach ($catalogs as $catalog) {
    $catalogIds[] = $catalog['id'];
}

$portfolioTable = new Portfolio_Model_DbTable_Portfolio();
$portfolios = $portfolioTable->getAll();

foreach ($portfolios as $portfolio) {
    if (!in_array($portfolio['portfolioCatalogId'], $catalogIds)) {
        $dbPortfolio = $portfolioTable->find($portfolio['id'])->current();
        $dbPortfolio->delete();
    }
}