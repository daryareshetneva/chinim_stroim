DROP TABLE IF EXISTS `Static`;
CREATE TABLE `Static` (
    `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` varchar(250) NOT NULL,
    `metaTitle` varchar(250) NOT NULL,
    `metaDescription` text NOT NULL,
    `content` text NOT NULL,
    `alias` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Feedback`;
CREATE TABLE `Feedback` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `phone` varchar(250) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Slider`;
CREATE TABLE `Slider` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `image` VARCHAR(250) NOT NULL,
  `position` SMALLINT NOT NULL,
  `url` VARCHAR(250) NOT NULL,
  `title` VARCHAR(250) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;