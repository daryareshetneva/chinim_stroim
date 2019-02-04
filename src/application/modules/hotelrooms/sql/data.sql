INSERT INTO `HotelRooms_Categories` (`id`, `title`, `alias`, `position`) VALUES
  (1,	'Стандарт',	'standart',	'1'),
  (2,	'Люкс',	'lyuks',	'2'),
  (3,	'Полулюкс',	'polulyuks',	'3');

INSERT INTO `HotelRooms_Photos` (`id`, `roomId`, `photo`, `position`) VALUES
  (1,	1,	'DIMA3539.jpg',	0),
  (2,	1,	'DIMA3545.jpg',	0),
  (3,	1,	'DIMA3552.jpg',	0),
  (4,	1,	'DIMA3555.jpg',	0);

INSERT INTO `HotelRooms_Properties` (`id`, `roomId`, `title`, `value`, `icon`, `position`) VALUES
  (1,	1,	'Прикроватные тумбочки',	'2',	'',	0),
  (2,	1,	'Стол, два кресла',	'',	'',	0),
  (6,	1,	'Электрокамин',	'',	'',	0),
  (4,	1,	'Плазменный телевизор',	'',	'',	0),
  (5,	1,	'Шкаф с зеркалами',	'',	'',	0),
  (7,	1,	'Минихолодильник',	'',	'',	0),
  (8,	1,	'Кондиционер',	'',	'',	0),
  (9,	1,	'Чайный прибор',	'',	'',	0),
  (10,	1,	'Чайник',	'',	'',	0),
  (11,	1,	'Чай, кофе, сахар ',	'',	'',	0),
  (12,	1,	'Халат, тапочки ',	'',	'',	0),
  (13,	1,	'Индивидуальны одноразовые туалетные принадлежности ',	'',	'',	0);

INSERT INTO `HotelRooms_Rooms` (`id`, `categoryId`, `title`, `alias`, `pricePerDay`, `pricePerHour`, `shortDescription`, `description`, `personAmount`, `photo`, `bed`, `fridge`, `jacuzzi`, `satTv`, `electrofireplace`, `sauna`, `wifi`, `shower`, `fireplace`, `conditioner`, `cupboard`, `minibar`) VALUES
  (1,	2,	'Номер 9',	'nomer-9',	'8000',	'1500',	'<p>Номер повышенной комфортности для самых требовательных посетителей. В номере есть мини-парная и джакузи.</p>\r\n',	'<p>Номер повышенной комфортности для самых требовательных посетителей. Идеально подойдет для семейной пары. Для отдыха вам будет предоставлена мини-парная, большая кровать и джакузи. Богатая комплектация номера, и оснащение всем необходимым для проживания делают этот номер настоящей находкой для гостей нашего города.</p>\r\n',	'2',	'DIMA3548.jpg',	1,	0,	1,	1,	0,	0,	1,	0,	1,	1,	0,	0);