DROP TABLE IF EXISTS `SocialNetworks`;
CREATE TABLE `SocialNetworks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(250) NOT NULL,
  `url` varchar(250) NOT NULL,
  `img` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Phones`;
CREATE TABLE `Phones` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `phone` varchar(250) NOT NULL,
  `isMain` tinyint(1) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;