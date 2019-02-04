DROP TABLE IF EXISTS `Transaction`;
CREATE TABLE `Transaction` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `status` enum( 'init', 'check', 'register', 'errorCheck', 'errorRegister', 'cancel' ),
    `type` enum( 'balance', 'order', 'delivery' ),
    `userId` int unsigned NOT NULL,
    `orderId` int unsigned NULL,
    `deliveryId` int unsigned NULL,
    `amount` decimal(7,2) unsigned NOT NULL,
    `description` text NOT NULL,
    `bankAmount` decimal(7,2) unsigned NOT NULL,
    `bankTrxId` varchar(250) NOT NULL,
    `bankLangCode` varchar(2) NOT NULL,
    `bankRrn` varchar(250) NOT NULL,
    `bankAuthCode` varchar(250) NOT NULL,
    `bankTrxDate` varchar(250) NOT NULL,
    `bankMaskedPan` varchar(250) NOT NULL,
    `bankCardholder` varchar(250) NOT NULL,
    `bankTs` varchar(250) NOT NULL,
    `ts` datetime NOT NULL,
    PRIMARY KEY ( `id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `TrxHistory`;
CREATE TABLE `TrxHistory` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `type` enum( 'add', 'dec', 'hold-add', 'hold-dec', 'order' ),
    `userId` int unsigned NOT NULL,
    `userFio` varchar(250) NOT NULL,
    `userEmail` varchar(250) NOT NULL,
    `orderId` int unsigned NULL,
    `trxId` int unsigned NULL,
    `deliveryId` int unsigned NULL,
    `amount` decimal(7,2) unsigned NOT NULL,
    `ts` timestamp NOT NULL,
    PRIMARY KEY ( `id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;