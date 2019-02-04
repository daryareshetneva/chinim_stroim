<?php
$this->_db->query('ALTER TABLE `ServicesImages` DROP COLUMN `categoryId`,
                                                ADD `serviceAlias` varchar(250) NOT NULL;');