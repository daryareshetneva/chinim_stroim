<?php
$this->_db->query("DROP TABLE IF EXISTS `Shop_Products`;");

$this->_db->query("CREATE TABLE `Shop_Products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` TINYINT unsigned NOT NULL,
  `titleSearch` varchar(250) NOT NULL,
  `alias` varchar(250) NOT NULL,
  `categoryId` int unsigned NOT NULL,
  `count` int unsigned NOT NULL,
  `deleted` int unsigned NOT NULL,
  `sale` TINYINT(1) unsigned NOT NULL,
  `metaTitle` varchar(255) NOT NULL,
  `metaKeywords` varchar(500) NOT NULL,
  `metaDescription` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
