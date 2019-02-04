<?php
$this->_db->query("ALTER TABLE `Settings` CHANGE `value` `value` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
INSERT INTO `cms`.`Settings` (`id`, `name`, `value`) VALUES (NULL, 'yandexMetrika', ''), (NULL, 'googleAnalytics', '');");