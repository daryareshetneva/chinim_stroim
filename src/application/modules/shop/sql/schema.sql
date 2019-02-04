DROP TABLE IF EXISTS `Shop_Categories`;
CREATE TABLE `Shop_Categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(250) NOT NULL,
  `alias` varchar(250) NOT NULL,
  `parentId` int unsigned NOT NULL,
  `description` text NOT NULL,
  `metaTitle` varchar(255) NOT NULL,
  `metaKeywords` varchar(500) NOT NULL,
  `metaDescription` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Shop_Products`;
CREATE TABLE `Shop_Products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(250) NOT NULL,
  `image` VARCHAR(250) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` TINYINT unsigned NOT NULL,
  `discount2` tinyint(3) unsigned NOT NULL,
  `titleSearch` varchar(250) NOT NULL,
  `alias` varchar(250) NOT NULL,
  `categoryId` int unsigned NOT NULL,
  `count` int unsigned NOT NULL,
  `deleted` TINYINT(1) unsigned NOT NULL,
  `sale` TINYINT(1) unsigned NOT NULL,
  `new` tinyint(1) unsigned NOT NULL,
  `productOfDay` tinyint(1) unsigned NOT NULL,
  `metaTitle` varchar(255) NOT NULL,
  `metaKeywords` varchar(500) NOT NULL,
  `metaDescription` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Shop_ProductImages`;
CREATE TABLE `Shop_ProductImages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `productId` int UNSIGNED NOT NULL,
  `image` VARCHAR(250) NOT NULL,
  `position` int UNSIGNED
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Shop_Filters`;
CREATE TABLE `Shop_Filters` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(250) NOT NULL,
  `alias` varchar(250) NOT NULL,
  `position` int unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Shop_FilterElements`;
CREATE TABLE `Shop_FilterElements` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(250) NOT NULL,
  `position` int unsigned NOT NULL,
  `filterId` int unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Shop_FilterProductsRelations`;
CREATE TABLE `Shop_FilterProductsRelations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `filterElementId` int unsigned NOT NULL,
  `productId` int unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Shop_Orders`;
CREATE TABLE `Shop_Orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `status` TINYINT(5) unsigned NOT NULL,
  `name` varchar(120) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `email` varchar(120) NOT NULL,
  `date` datetime NOT NULL,
  `userId` int unsigned NOT NULL,
  `comment` text NOT NULL,
  `requisites` VARCHAR(250) NOT NULL,
  `userType` tinyint(1) NOT NULL,
  `delivery` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Shop_Ordered_Products`;
CREATE TABLE `Shop_Ordered_Products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `idOrder` int unsigned NOT NULL,
  `idProduct` int unsigned NOT NULL,
  `title` varchar(250) NOT NULL,
  `count` int unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` TINYINT unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;