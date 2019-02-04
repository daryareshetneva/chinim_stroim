<?php
// Migration file
// Use $this->_db->query() to run migration
$this->_db->query("INSERT INTO `Static` (`title`, `content`, `alias`, `metaTitle`, `metaDescription`) VALUES ('Домашняя страница', 'Что-то домашнее', 'home', 'home', 'home');");
