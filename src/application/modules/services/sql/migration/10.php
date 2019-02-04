<?php
$this->_db->query('ALTER TABLE `Services_Items` DROP COLUMN `serviceAlias`,
                                                ADD `category_id` varchar(250) NOT NULL;');