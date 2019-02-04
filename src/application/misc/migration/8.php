<?php

$this->_db->query('ALTER TABLE `Blog` ADD FULLTEXT (
`title`,
`shortDescription`,
`description`
)');

$this->_db->query('ALTER TABLE `News` ADD FULLTEXT (
`title`,
`content`,
`description`
)');

$this->_db->query('ALTER TABLE `Shop_Products` ADD FULLTEXT (
`title`,
`description`
)');

$this->_db->query('ALTER TABLE `Static` ADD FULLTEXT (
`title`,
`content`
)');