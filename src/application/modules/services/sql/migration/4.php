<?php
$this->_db->query('ALTER TABLE `Services_Tree`
                    ADD `leftkey` int unsigned not null,
                    add `rightkey` int unsigned not NULL,
                    add `level` int unsigned not NULL;');