<?php
$this->_db->query("DROP TABLE IF EXISTS `Shop_ProductImages`;
CREATE TABLE `Shop_ProductImages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `productId` int UNSIGNED NOT NULL,
  `image` VARCHAR(250) NOT NULL,
  `position` int UNSIGNED
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");