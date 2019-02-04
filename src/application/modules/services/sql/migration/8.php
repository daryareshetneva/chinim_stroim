<?php
$this->_db->query("CREATE TABLE `ServicesImages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `categoryId` int unsigned NOT NULL,
  `image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");