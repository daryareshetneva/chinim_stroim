DROP TABLE IF EXISTS `Photos`;
CREATE TABLE `Photos` (
	`id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`src` varchar(250) NOT NULL,
	`cutSrc` varchar(250) NOT NULL,
	`title` text NULL DEFAULT NULL,
	`desc` text NULL DEFAULT NULL,
	`date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Photo_Categories`;
CREATE TABLE `Photo_Categories` (
	`id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`pid` int unsigned NULL DEFAULT 0,
	`title` varchar(250) NOT NULL,
	`desc` text NULL DEFAULT NULL,
	`alias` varchar(250) NULL DEFAULT NULL,
	`metaTitle` varchar(250) NULL DEFAULT NULL,
	`metaDesc` varchar(250) NULL DEFAULT NULL,
	`date` datetime NOT NULL,
	`image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Photo_Relations`;
CREATE TABLE `Photo_Relations` (
	`id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`photo_id` int unsigned NOT NULL,
	`category_id` int unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;