DROP TABLE IF EXISTS `Services_Tree`;
CREATE TABLE `Services_Tree` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `alias`     VARCHAR(250) NOT NULL UNIQUE,
  `title`      VARCHAR(250) NOT NULL,
  `description` text NOT NULL,
  `metaTitle` varchar(250) NOT NULL,
  `metaDescription` varchar(250) NOT NULL,
  `leftkey` INT unsigned NOT null,
  `rightkey` int UNSIGNED NOT NULL,
  `level` INT UNSIGNED NOT NULL,
  `parent_id` INT UNSIGNED NOT NULL,
  `position`  INT UNSIGNED NOT NULL,
  `categoryPhoto` varchar(250) NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
DROP TABLE IF EXISTS `Services_Items`;
CREATE TABLE `Services_Items` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title`      VARCHAR(250) NOT NULL,
  `description` TEXT NOT NUll,
  `price` VARCHAR(250) NOT NULL,
  `term` VARCHAR(250) NOT NULL,
  `serviceMainPhoto` VARCHAR(250) NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  `alias` VARCHAR(250) NOT NULL UNIQUE,
  `meta_title` VARCHAR(250) NOT NULL,
  `meta_description` VARCHAR(250) NOT NULL,
  `position`  INT UNSIGNED NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
DROP TABLE IF EXISTS `ServicesImages`;
CREATE TABLE `ServicesImages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `serviceAlias` varchar(250) NOT NULL,
  `image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;