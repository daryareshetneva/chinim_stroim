<?php

$this->_db->query("ALTER TABLE `Shop_Orders`
ADD `userType` tinyint(1) NOT NULL,
ADD `delivery` tinyint(1) NOT NULL AFTER `userType`;");