<?php

$this->_db->query("UPDATE `Static` SET
`title` = 'Контакты',
`metaTitle` = 'Контакты',
`metaDescription` = 'contacts',
`content` = '<img src=\"../images/public/contacts_map1.jpg\" alt=\"\" />\r\n					<div class=\"info_map\">\r\n						<div>\r\n							адрес: 634041, Томск, <br />\r\n							ул. Сибирская, 40, магазин «Субару»\r\n						</div>	\r\n						<div>\r\n							телефон  <b>+7 913 850 38 30</b> <br />\r\n							телефон    8 3822 <b>26 25 44</b>\r\n						</div>\r\n					</div>'
 WHERE `alias` = 'contacts';");