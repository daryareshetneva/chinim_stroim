<?php
$this->_db->query("DROP TABLE IF EXISTS `Shop_Orders`;");

$this->_db->query("CREATE TABLE `Shop_Orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `status` TINYINT unsigned NOT NULL,
  `name` varchar(120) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `email` varchar(120) NOT NULL,
  `date` datetime NOT NULL,
  `userId` int unsigned NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$this->_db->query("DROP TABLE IF EXISTS `Shop_Ordered_Products`;");

$this->_db->query("CREATE TABLE `Shop_Ordered_Products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `idOrder` int unsigned NOT NULL,
  `idProduct` int unsigned NOT NULL,
  `title` varchar(250) NOT NULL,
  `count` int unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` TINYINT unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
