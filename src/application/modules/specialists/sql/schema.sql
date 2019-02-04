DROP TABLE IF EXISTS `Specialists`;
CREATE TABLE `Specialists` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `fio` VARCHAR(250) NOT NULL,
  `photo` VARCHAR(250) NOT NULL,
  `photoSmall` VARCHAR(250) NOT NULL,
  `shortDescription` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  `position` int NOT NULL,
  `post` VARCHAR (250) NOT NULL,
  `metaTitle` VARCHAR (250) NOT NULL,
  `metaDescription` TEXT NOT NULL,
  `metaKeywords` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
