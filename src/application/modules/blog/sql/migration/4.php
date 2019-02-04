<?php
$this->_db->query('ALTER TABLE `Diy` ADD `metaTitle` varchar(250) NOT NULL');
$this->_db->query('UPDATE `Diy` SET `metaTitle` = `title`');

