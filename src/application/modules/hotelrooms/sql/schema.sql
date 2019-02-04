DROP TABLE IF EXISTS `HotelRooms_Categories`;
CREATE TABLE `HotelRooms_Categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(250) NOT NULL,
  `alias` varchar(250) NOT NULL,
  `position` VARCHAR(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `HotelRooms_Rooms`;
CREATE TABLE `HotelRooms_Rooms` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `categoryId` int unsigned NOT NULL,
  `title` varchar(250) NOT NULL,
  `alias` varchar(250) NOT NULL,
  `pricePerDay` varchar(250) NOT NULL,
  `pricePerHour` varchar(250) NOT NULL,
  `shortDescription` text NOT NULL,
  `description` longtext NOT NULL,
  `personAmount` varchar(250) NOT NULL,
  `photo` varchar(250) NOT NULL,
  `bed` tinyint(1) NOT NULL,
  `fridge` tinyint(1) NOT NULL,
  `jacuzzi` tinyint(1) NOT NULL,
  `satTv` tinyint(1) NOT NULL,
  `electrofireplace` tinyint(1) NOT NULL,
  `sauna` tinyint(1) NOT NULL,
  `wifi` tinyint(1) NOT NULL,
  `shower` tinyint(1) NOT NULL,
  `fireplace` tinyint(1) NOT NULL,
  `conditioner` tinyint(1) NOT NULL,
  `cupboard` tinyint(1) NOT NULL,
  `minibar` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `HotelRooms_Photos`;
CREATE TABLE `HotelRooms_Photos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `roomId` int unsigned NOT NULL,
  `photo` varchar(250) NOT NULL,
  `position` int unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `HotelRooms_Properties`;
CREATE TABLE `HotelRooms_Properties` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `roomId` int unsigned NOT NULL,
  `title` varchar(250) NOT NULL,
  `value` varchar(250) NOT NULL,
  `icon` varchar(250) NOT NULL,
  `position` int unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;