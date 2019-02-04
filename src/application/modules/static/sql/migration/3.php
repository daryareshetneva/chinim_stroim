<?php

$this->_db->query("DELETE FROM `Static` WHERE 1");

$this->_db->query("INSERT INTO `Static` (`title`, `content`, `alias`, `metaTitle`, `metaDescription`) VALUES 
('Главная', 'Контент', 'home', 'home', 'home'),
('Как сделать заказ?', 'Контент', 'how-to-make-order', 'how-to-make-order', 'how-to-make-order'),
('Информация для покупателей', 'Контент', 'information', 'information', 'information'),
('Помощь', 'Контент', 'help', 'help', 'help');");
