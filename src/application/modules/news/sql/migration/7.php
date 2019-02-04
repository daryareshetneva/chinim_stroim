<?php
$this->_db->query("CREATE TABLE `NewsTags` (
  `idNews` int(10) unsigned NOT NULL,
  `idTags` int(11) unsigned NOT NULL,
  KEY `idTags` (`idTags`),
  KEY `idNews` (`idNews`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");