<?php
$this->_db->query("UPDATE `Static` SET
`title` = 'Контакты',
`metaTitle` = 'Контакты',
`metaDescription` = 'contacts',
`content` = '<img src=\"../images/public/contacts_map1.jpg\" alt=\"\" />\r\n					<div class=\"info_map\">\r\n						<div>\r\n							адрес: 634041, Томск, <br />\r\n							ул. Сибирская, 40, магазин «Субару»\r\n						</div>	\r\n						<div>\r\n							телефон  <b>+7 913 850 38 30</b> <br />\r\n							телефон    8 3822 <b>26 25 44</b>\r\n						</div>\r\n					</div>'
 WHERE `alias` = 'contacts';");

$this->_db->query("UPDATE `Static` SET
`title` = 'Как оформить заказ?',
`metaTitle` = 'Как оформить заказ?',
`metaDescription` = 'how-to-make-order'
 WHERE `alias` = 'how-to-make-order';");

$this->_db->query("UPDATE `Static` SET
`title` = 'Информация для покупателей',
`metaTitle` = 'Информация для покупателей',
`metaDescription` = 'information',
`content` = 'Контент'
WHERE `alias` ='information';");

$this->_db->query("UPDATE `Static` SET
`title` = 'Как работать с сайтом',
`metaTitle` = 'Как работать с сайтом',
`metaDescription` = 'help',
`content` = '<br /><br />
					<h4>Все очень просто</h4>
					<img src=\"../images/public/how_to_work.jpg\" alt=\"\" style=\"float: right;\" />
					<br />
					<p>
						1. если  вы знаете номер запчасти которая вам нужна, вы просто вводите <br />
						его в строку Поиск по Артикулу. Мы предложим вам несколько вариантов, которые будут <br />
						отличаться ценой и сроками доставки, как правило чем меньше срок поставки тем цена выше.  <br />
						Вы выбираете вариант который вас устраивает по цене и сроку, добавляете его в корзину. <br />
					</p>
					<p>
						2. Второй вариант если вы не знаете номер запчасти, для поиска вы можете воспользоваться<br />
						on-line  каталогами,  в которых используя VIN номер или номер кузова вашего автомобиля <br />
						вы можете найти номер нужной вам детали. 
					</p>
					
					<p class=\"seperator\"></p>
					
					<p>
						Оплатить заказ вы можете либо наличными в офисе,<br />
						либо банковской картой VISA, MAESTRO, UNION CARD  и т.д. 
					</p>
					
					<p>
						В течении всего срока  выполнения вашего заказа вы можете отслеживать его состояние <br />
						на нашем сайте www.gaika.su в вашем Личном кабинете, после прихода запчасти на наш склад <br />
						вы можете заказать Доставку по городу, для заказов от 1000р она бесплатная.
					</p>
					
					<br />
					
					<p style=\"background: #009de7; color: #ffffff; min-height: 55px; width: 550px; border-radius: 5px;\">
						<img src=\"../images/public/blue_message.jpg\" style=\"float: left; margin: 10px\" alt=\"\" />
						<span style=\"padding-top: 5px; display: block;\">
						Если вы не можете найти номер интересующей вас запчасти вы <br />
						можете воспользоваться помощью наших On-Line консультантов
						</span>
					</p>'
WHERE `alias` = 'help';");

$this->_db->query("UPDATE `Static` SET
`title` = 'Оптовикам',
`metaTitle` = 'Оптовикам',
`metaDescription` = 'optovikam',
`content` = '<div style=\"display: inline-block; width: 700px; padding-top: 25px;\">\r\n						Если вы представляете автосервис или магазин, наша компания <a href=\"http://gaika.su\" style=\"color: #1D1D1D;\">www.Gaika.su</a>\r\n						<br />\r\n						готова предоставить Вам дополнительные скидки, \r\n						<br />\r\n						запросы можно присылать на адрес <a href=\"mailto:info@gaika.su\">info@gaika.su</a>\r\n					</div>\r\n					<img src=\"../images/public/optovikam.png\" alt=\"\" style=\"display: inline-block; float: right;\" />'
WHERE `alias` = 'optovikam';");

$this->_db->query("UPDATE `Static` SET
`title` = 'Поставщикам',
`metaTitle` = 'Поставщикам',
`metaDescription` = 'postovshikam',
`content` = '<h4>Приглашаем к сотрудничеству</h4>
					<img src=\"../images/public/postavshikam1.jpg\" alt=\"\" style=\"float: right; margin-top: -20px;\" />
					<p>
						Уважаемые поставщики и производители автомобильных запчастей и аксессуаров!<br />
						Интернет-магазин Gaika.su  приглашает Вас к сотрудничеству.
					</p>
					<p>						
						Наша компания молодая и быстроразвивающаяся, стремительно расширяет свое присутствие <br />
						на Сибирском рынке автозапчастей предлагая потребителям широкий спектр  автозапчастей <br />
						для автомобилей европейских, азиатских, японских производителей и заинтересована в <br />
						расширении круга своих партнеров.
					</p>
					<p>
						Мы предлагаем нашим клиентам on-line каталоги для поиска деталей, полностью <br />
						компьютеризированную систему приема и обработки заказов.
					</p>
					
					<br />
						
					<p class=\"seperator\"></p>
					
					<h4>Интересные условия</h4>
					<img src=\"../images/public/postavshikam2.jpg\" style=\"float: right;\" />					
					
					<p>
						Мы рассматриваем сотрудничество с поставщиками и производителями как одно из основных <br />
						направлений развития компании. Мы понимаем заинтересованность поставщиков в долгосрочном <br />
						сотрудничестве и готовы предоставить все необходимые условия для такого сотрудничества.
					</p>
					<p>
						Мы с интересом рассматриваем все предложения о сотрудничестве, мы готовы приложить <br />
						все усилия для увеличения совместного оборота
					</p>
					<p>
						За дополнительной информацией обращайтесь:<br />
						Руководитель отдела взаимодействия с поставщиками <a href=\"mailto:supply@gaika.su\">supply@gaika.su</a>
					</p>'
WHERE `alias` = 'postovshikam';");