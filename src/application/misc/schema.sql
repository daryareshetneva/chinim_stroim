SET NAMES utf8;

DROP TABLE IF EXISTS `Modules`;
CREATE TABLE `Modules` (
    `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(250) NOT NULL,
    `version` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Resources`;
CREATE TABLE `Resources` (
    `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `module` varchar(250) not null,
    `controller` varchar(250) not null,
    `action` varchar(250) not null,
    `administrator` tinyint(1) unsigned NOT NULL,
    `manager` tinyint(1) unsigned NOT NULL,
    `user` tinyint(1) unsigned NOT NULL,
    `guest` tinyint(1) unsigned NOT NULL,
    `active` tinyint(1) unsigned NOT NULL,
    `alias` varchar(250) NOT NULL,
    `title` varchar(250) NOT NULL,
    `metaTitle` varchar(250) NOT NULL,
    `metaDescription` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Settings`;
CREATE TABLE `Settings` (
    `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(250) NOT NULL,
    `value` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Managers`;
CREATE TABLE `Managers` (
    `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `email` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Files`;
CREATE TABLE `Files` (
    `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` varchar(250) NOT NULL,
    `hash` VARCHAR(250) not null,
    `ext` VARCHAR(250) NOT NULL,
    `path` VARCHAR(250) not null,
    `date` DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `FileRelations`;
CREATE TABLE `FileRelations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(250) NOT NULL,
  `file_id` int UNSIGNED NOT NULL,
  `module_id` int UNSIGNED NOT NULL,
  `action_id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `FileAction`;
CREATE TABLE `FileAction` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(250) NOT NULL,
  `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;