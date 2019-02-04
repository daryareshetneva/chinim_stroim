<?php
$this->_db->query('ALTER TABLE `Portfolio` ADD `metaTitle` varchar(250) NOT NULL');
$this->_db->query('UPDATE `Portfolio` SET `metaTitle` = `shortTitle`');
