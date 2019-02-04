<?php
//change place for keep default version
$this->_db->query('ALTER TABLE `Resources`
    ADD `active` tinyint(1) NOT NULL;');
