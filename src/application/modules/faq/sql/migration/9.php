<?php
$this->_db->query('ALTER TABLE `Faq`
ADD `fio` varchar(250) NOT NULL,
ADD `email` varchar(250) NOT NULL AFTER `fio`,
ADD `show` tinyint(1) NOT NULL;');