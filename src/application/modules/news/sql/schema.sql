DROP TABLE IF EXISTS `News`;
CREATE TABLE `News` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(250) NOT NULL,
  `content` text NOT NULL,
  `description` text NOT NULL,
  `date` datetime NOT NULL,
  `userId` int(10) unsigned NOT NULL,
  `alias` VARCHAR(250) NOT NULL,
  `metaTitle` varchar(250),
  `metaKeywords` text NOT NULL,
  `metaDescription` text NOT NULL,
  `image` VARCHAR(250)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
