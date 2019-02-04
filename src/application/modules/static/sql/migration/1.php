<?php
// Migration file
// Use $this->_db->query() to run migration

$this->_db->query("INSERT INTO `Static` (`title`, `content`, `alias`, `metaTitle`, `metaDescription`) VALUES 
    ('Как работать с сайтом', '<br /><br />
					<h4>Все очень просто</h4>
					<img src=\"../images/public/how_to_work.jpg\" style=\"float: right;\" />
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
						<img src=\"../images/public/blue_message.jpg\" style=\"float: left; margin: 10px\" />
						<span style=\"padding-top: 5px; display: block;\">
						Если вы не можете найти номер интересующей вас запчасти вы <br />
						можете воспользоваться помощью наших On-Line консультантов
						</span>
					</p>
    ', 'help', 'Как работать с сайтом', 'Как работать с сайтом');");

$this->_db->query("INSERT INTO `Static` (`title`, `content`, `alias`, `metaTitle`, `metaDescription`) VALUES 
    ('О Компании', '
    <img src=\"../images/public/about.jpg\" style=\"float: right\" />
					<p>
						Интернет-магазин Gaika.su  начал свою работу в 2008  году.  С самого начала главной
						целью было предложить нашим клиентам самый широкий спектр автомобильных
						запасных частей и аксессуаров, а развитие интернет–технологий дало возможность
						максимально упростить и ускорить процесс покупки.
					</p>
					
					<p>
						Компания быстро росла, и в 2014 году вышла в Интернет.
						В основе проекта Gaika.su:  современные информационные технологии, собственные
						программные разработки, накопленная за годы работы база поставщиков,
						высококвалифицированный коллектив — мы делаем все для того, чтобы Вы были
						довольны нашей работой.
					</p>
					
					<h4>Мы предлагаем нашим клиентам : </h4>
					<p>
						Самую совершенную систему on-line поиска и заказа автозапчастей
						<ul>
							<li>
								Обширный on-line каталог автозапчастей для автомобилей европейских, японских и
								корейских производителей.
							</li>	
							<li>
								Возможность поиска интересующих деталей различными способами: запрос по VIN 
								автомобиля, номеру запчасти, по схемам в каталоге.
							</li>
							<li>
								Получение максимально полной информации о детали — наличие аналогов у
								различных производителей, применяемости, ценах и сроках поставки.
							</li>
						</ul>
					</p>
					
					<h4>Огромный ассортимент</h4>
					<p>
						<ul>
							<li>
								В наших прайс-листах более 10 млн наименований оригинальных и неоригинальных зап.частей ..
							</li>
							<li>
								Мы сотрудничаем с большим количеством поставщиков из ОАЭ и Японии 
							</li>
							<li>
								Удобная форма оплаты 
							</li>
						</ul>
						Вы можете оплатить заказ с помощью банковской карты VISA , MASTER CARD , MAESTRO и т.д.
					</p>		
					
					<h4>Бесплатная доставка </h4>
					<p>
						<ul>
							<li>
								Бесплатная доставка по Томску .
							</li>
							<li>
								Минимальные сроки доставки заказа любым удобным для клиента способом (авто,
								авиа, ж/д, курьерская доставка) более чем в 200 городов страны.
							</li>
							<br />
							<span style=\"width: 650px; margin-top: -10px; display: inline-block;\">
								Работая с нами и нашими партнерами, Вы всегда можете быть уверены в грамотном и
								вежливом обслуживании, быстроте и точности исполнения заказов, высоком качестве 
								продукции. Мы будем рады видеть Вас среди наших клиентов!
							</span>
						</ul>
						<br />							
					</p>
    ', 'about', 'О Компании', 'О Компании');");

$this->_db->query("INSERT INTO `Static` (`title`, `content`, `alias`, `metaTitle`, `metaDescription`) VALUES 
    ('Оптовикам', '
    <div style=\"display: inline-block; width: 700px; padding-top: 25px;\">
						Если вы представляете автосервис или магазин, наша компания <a href=\"http://gaika.su\" style=\"color: #1D1D1D;\">www.Gaika.su</a>
						<br />
						готова предоставить Вам дополнительные скидки, 
						<br />
						запросы можно присылать на адрес <a href=\"mailto:info@gaika.su\">info@gaika.su</a>
					</div>
					<img src=\"../images/public/optovikam.png\" style=\"display: inline-block; float: right;\" />
    ', 'optovikam', 'Оптовикам', 'Оптовикам'),
    ('Поставщикам', '
    <h4>Приглашаем к сотрудничеству</h4>
					<img src=\"../images/public/postavshikam1.jpg\" style=\"float: right; margin-top: -20px;\" />
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
					</p>
    ', 'postovshikam', 'Поставщикам', 'Поставщикам');
");