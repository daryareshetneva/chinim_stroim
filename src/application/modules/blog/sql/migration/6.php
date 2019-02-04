<?php
$this->_db->query("CREATE TABLE `BlogTags` (
  `idBlog` int(10) unsigned NOT NULL,
  `idTags` int(11) unsigned NOT NULL,
  KEY `idTags` (`idTags`),
  KEY `idBlog` (`idBlog`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `Tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");