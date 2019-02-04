<?php
$this->_db->query("ALTER TABLE `Photo_Categories`
        ADD `image` varchar(250) NOT NULL;");