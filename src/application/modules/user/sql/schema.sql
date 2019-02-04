DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
    `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `type` enum('administrator', 'manager', 'user') NOT NULL DEFAULT 'user',
    `password` varchar(250) NOT NULL,
    `email` varchar(250) NOT NULL,
    `firstName` varchar(250) NOT NULL,
    `lastName` VARCHAR(250) NOT NULL,
    `patronymic` VARCHAR(250) NOT NULL,
    `phone` varchar(20) NOT NULL,
    `discount` decimal(4,3) unsigned NOT NULL DEFAULT 1,
    `registrationDate` datetime NOT NULL,
    `lastvisitDate` datetime NOT NULL,
    `provider` enum('none', 'vk','fb','google','yandex') NOT NULL,
    `uid` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Cities`;
CREATE TABLE `Cities` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `city` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `UserAddresses`;
CREATE TABLE `UserAddresses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userId` int unsigned NOT NULL,
  `cityId` int unsigned NOT NULL,
  `address` text NOT NULL,
  `index` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
