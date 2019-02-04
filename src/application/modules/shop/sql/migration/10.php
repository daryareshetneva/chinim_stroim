<?php

$this->_db->query("ALTER TABLE `Shop_Orders`
ADD `requisites` varchar(250) NOT NULL;");