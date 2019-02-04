<?php
$this->_db->query('ALTER TABLE `Faq` CHANGE `faq` `faq` text NOT NULL AFTER `id`;');