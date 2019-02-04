<?php

$this->_db->query("ALTER TABLE `News` 
        ADD `description` text NOT NULL;");