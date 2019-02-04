<?php
$this->_db->query('ALTER TABLE `Portfolio` ADD `price` varchar(250) NOT NULL,
                                           ADD `date` datetime NOT NULL;');