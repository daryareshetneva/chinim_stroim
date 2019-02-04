<?php

$this->_db->query("
    ALTER TABLE `TrxHistory`
        CHANGE `type` `type` enum( 'add', 'dec', 'hold-add', 'hold-dec', 'order' );
");