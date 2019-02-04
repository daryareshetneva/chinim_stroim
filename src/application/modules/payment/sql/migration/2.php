<?php

$this->_db->query( "
    DROP TABLE IF EXISTS `TrxHistory`;
    CREATE TABLE `TrxHistory` (
        `id` int unsigned NOT NULL AUTO_INCREMENT,
        `type` enum( 'add', 'order' ),
        `userId` int unsigned NOT NULL,
        `orderId` int unsigned NULL,
        `trxId` int unsigned NULL,
        `amount` decimal(7,2) unsigned NOT NULL,
        `ts` datetime NOT NULL,
        PRIMARY KEY ( `id` ),
        FOREIGN KEY ( `userId` ) REFERENCES `Users`( `id` ),
        FOREIGN KEY ( `orderId` ) REFERENCES `Orders`( `id` ),
        FOREIGN KEY ( `trxId` ) REFERENCES `Transaction`( `id` )
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;" );
$this->_db->query( "ALTER TABLE `Transaction`
    ADD `type` enum( 'balance', 'order' ),
    CHANGE `orderAmount` `amount` decimal(7,2) unsigned NOT NULL,
    CHANGE `ts` `ts` datetime NOT NULL,
    CHANGE `orderId` `orderId` int unsigned NULL;" );