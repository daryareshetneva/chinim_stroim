DROP TABLE IF EXISTS `Certificates`;
CREATE TABLE `Certificates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(250) NOT NULL,
  `image` VARCHAR(250) NOT NULL,
  `description` TEXT NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
