<?php 

$this->_db->query("CREATE TABLE `PortfolioImages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `portfolioId` int unsigned NOT NULL,
  `image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
