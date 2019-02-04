<?php
// Migration file
// Use $this->_db->query() to run migration

$this->_db->query("ALTER TABLE `Photos`
        ADD `cutSrc` varchar(250) NOT NULL;");