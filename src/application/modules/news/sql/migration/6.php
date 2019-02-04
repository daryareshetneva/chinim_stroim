<?php
$this->_db->query('ALTER TABLE `News` ADD `metaTitle` varchar(250) NOT NULL');
$this->_db->query('UPDATE `News` SET `metaTitle` = `title`');
$this->_db->query('ALTER TABLE `News` ADD `metaDescription` text NOT NULL');
