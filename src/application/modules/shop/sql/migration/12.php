<?php

$this->_db->query("
ALTER TABLE `Shop_Products`
ADD `discount2` tinyint(3) unsigned NOT NULL AFTER `discount`,
ADD `new` tinyint(1) unsigned NOT NULL AFTER `sale`,
ADD `productOfDay` tinyint(1) unsigned NOT NULL AFTER `new`;
");