<?php

$this->_db->query('ALTER TABLE `Resources`
    ADD `alias` varchar(250) NOT NULL,
    ADD `title` varchar(250) NOT NULL,
    ADD `metaTitle` varchar(250) NOT NULL,
    ADD `metaDescription` text NOT NULL;');