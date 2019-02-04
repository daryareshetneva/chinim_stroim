<?php

$this->_db->query("DROP TABLE IF EXISTS `PortfolioComments`;
CREATE TABLE `PortfolioComments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `comment` text NOT NULL,
  `reply` text NOT NULL,
  `new` tinyint NOT NULL,
  `date` date NOT NULL,
  `status` tinyint(1) NOT NULL,
  `portfolioId` int unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
