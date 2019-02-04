<?php

$this->_db->query("ALTER TABLE `Reviews`
CHANGE `review` `shortAnswer` text COLLATE 'utf8_general_ci' NOT NULL AFTER `id`");