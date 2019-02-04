<?php

$this->_db->query("
    ALTER TABLE `Transaction`
        ADD `deliveryId` int unsigned NULL,
        CHANGE `type` `type` enum( 'balance', 'order', 'delivery' );
");

$this->_db->query("
    ALTER TABLE `TrxHistory`
        ADD `deliveryId` int unsigned NULL,
        CHANGE `type` `type` enum( 'add', 'order', 'delivery' );
");