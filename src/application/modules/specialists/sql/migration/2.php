<?php
// Migration file
// Use $this->_db->query() to run migration

$this->_db->query("ALTER TABLE `Specialists`
ADD `position` int NOT NULL;");

$this->_db->query("UPDATE `Specialists` SET `position` = 1 WHERE `id` = 3");
$this->_db->query("UPDATE `Specialists` SET `position` = 2 WHERE `id` = 1");
$this->_db->query("UPDATE `Specialists` SET `position` = 3 WHERE `id` = 2");
$this->_db->query("UPDATE `Specialists` SET `position` = 4 WHERE `id` = 5");
$this->_db->query("UPDATE `Specialists` SET `position` = 5 WHERE `id` = 4");
