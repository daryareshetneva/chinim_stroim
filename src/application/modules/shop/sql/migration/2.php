<?php
$this->_db->query("DROP TABLE IF EXISTS `Shop_Categoriess`;
CREATE TABLE `Shop_Categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(250) NOT NULL,
  `alias` varchar(250) NOT NULL,
  `parentId` int unsigned NOT NULL,
  `description` text NOT NULL,
  `metaTitle` varchar(250) NOT NULL,
  `metaKeywords` varchar(500) NOT NULL,
  `metaDescription` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");