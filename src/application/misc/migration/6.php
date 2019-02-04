<?php

$this->_db->query('UPDATE `Settings`
    SET `name` = "mailSmtp"
    WHERE `name` = "mailTypeSSL";');