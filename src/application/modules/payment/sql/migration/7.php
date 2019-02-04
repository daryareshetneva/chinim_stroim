<?php

$this->_db->query("
    ALTER TABLE `TrxHistory`
        ADD `userFio` varchar(250) NOT NULL,
        ADD `userEmail` varchar(250) NOT NULL;
");

$this->_db->query("
    UPDATE `TrxHistory` as t1, `Users` as t2
        SET t1.`userFio` = t2.`fio`, t1.`userEmail` = t2.`email`
        WHERE t1.`userId` = t2.`id`;
");