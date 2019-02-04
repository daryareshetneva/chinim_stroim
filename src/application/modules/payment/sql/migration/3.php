<?php

$this->_db->query( "ALTER TABLE `Transaction`
    ADD `bankMaskedPan` varchar(250) NOT NULL,
    ADD `bankCardholder` varchar(250) NOT NULL;" );