<?php
$this->_db->query("ALTER TABLE `Shop_Products`
ADD `image` varchar(250) COLLATE 'utf8_general_ci' NOT NULL AFTER `title`,
CHANGE `description` `description` text NOT NULL AFTER `image`;");