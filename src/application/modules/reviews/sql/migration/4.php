<?php

$this->_db->query("ALTER TABLE `Reviews`
ADD `description` text NOT NULL;");