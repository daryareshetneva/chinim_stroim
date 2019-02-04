DROP TABLE IF EXISTS `Faq`;
CREATE TABLE `Faq` (
`id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
`question` text NOT NULL,
`answer` text NOT NULL,
`questionDate` datetime NOT NULL,
`answerDate` datetime NOT NULL,
`userLogin` int unsigned NOT NULL,
`adminLogin` int unsigned NOT NULL,
`fio` varchar(250) NOT NULL,
`email` varchar(250) NOT NULL,
`show` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;