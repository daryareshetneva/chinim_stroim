<?php

$this->_db->query(
    "ALTER TABLE `TrxHistory`
	CHANGE `ts` `ts` BIGINT NOT NULL;"
);