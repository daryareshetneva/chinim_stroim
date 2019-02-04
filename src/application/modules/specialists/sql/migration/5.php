<?php
/**
 * Created by PhpStorm.
 * User: koro
 * Date: 06.07.18
 * Time: 11:51
 */
//Добавление полей для метатегов
$this->_db->query('ALTER TABLE `Specialists` ADD `metaTitle` VARCHAR (250);');
$this->_db->query('ALTER TABLE `Specialists` ADD `metaDescription` TEXT;');
$this->_db->query('ALTER TABLE `Specialists` ADD `metaKeywords` TEXT;');