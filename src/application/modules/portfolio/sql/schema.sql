DROP TABLE IF EXISTS `PortfolioCatalogs`;
CREATE TABLE `PortfolioCatalogs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(250) NOT NULL,
  `alias` varchar(250) NOT NULL,
  `metaTitle` varchar(250) NULL,
  `metaDescription` text NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Portfolio`;
CREATE TABLE `Portfolio` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `alias` varchar(250) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `portfolioCatalogId` int unsigned NOT NULL,
  `metaTitle` varchar(250) NOT NULL,
  `metaDescription` text NOT NULL,
  `price` varchar(250) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `PortfolioImages`;
CREATE TABLE `PortfolioImages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `portfolioId` int unsigned NOT NULL,
  `image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `PortfolioComments`;
CREATE TABLE `PortfolioComments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `comment` text NOT NULL,
  `reply` text NOT NULL,
  `new` tinyint NOT NULL,
  `date` date NOT NULL,
  `status` tinyint(1) NOT NULL,
  `portfolioId` int unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
