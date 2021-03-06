<?php
$this->_db->query("
UPDATE `Static` SET
`title` = 'О Компании',
`metaTitle` = 'О Компании',
`metaDescription` = 'about',
`content` = '<img src=\"../images/public/about.jpg\" alt=\"\" style=\"float: right\" />
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
					</p>'
WHERE `alias` = 'about';
");