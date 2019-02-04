DROP TABLE IF EXISTS `Blog`;
CREATE TABLE `Blog` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(250) NOT NULL,
  `shortDescription` text NOT NULL,
  `description` text NOT NULL,
  `image` varchar(250) NOT NULL,
  `date` date NOT NULL,
  `alias` varchar(250) NOT NULL,
  `metaTitle` varchar(250) NOT NULL,
  `metaDescription` text NOT NULL,
  `metaKeywords` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
