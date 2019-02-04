<?php

$this->_db->query("INSERT INTO `Static` (`title`, `content`, `alias`, `metaTitle`, `metaDescription`) VALUES 
('Контакты', '', 'contacts', 'contacts', 'contacts'),
('О Компании', '', 'about', 'about', 'about'),
('Оптовикам', '', 'optovikam', 'optovikam', 'optovikam'),
('Поставщикам', '', 'postovshikam', 'postovshikam', 'postovshikam');");