<?php
$this->_db->query('ALTER TABLE `Portfolio` ADD `alias` varchar(250) NOT NULL');
$this->_db->query('UPDATE `Portfolio` SET `alias` = `id`');