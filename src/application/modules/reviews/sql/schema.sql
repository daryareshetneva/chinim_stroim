DROP TABLE IF EXISTS `Reviews`;
CREATE TABLE `Reviews` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(250) NOT NULL,
  `answer` text NOT NULL,
  `photo` VARCHAR(250) NOT NULL,
  `mark` VARCHAR (250) NOT NULL,
  `reviewDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
