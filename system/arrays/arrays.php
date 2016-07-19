<?php
/*
=====================================================
| CMS CATHARSIS SHOP - by D0Gmatist
-----------------------------------------------------
| http://d0gmatist.pro/
===== ===== ===== ===== ===== ===== ===== ===== =====
| Copyright (c) 2016
-----------------------------------------------------
| Данный код защищен авторскими правами
===== ===== ===== ===== ===== ===== ===== ===== =====
| Файл: arrays.php
-----------------------------------------------------
| Назначение: Основные массивы
=====================================================
*/

if ( ! defined ( 'CATHARSIS_SHOP' ) ) { 
	die ( 'Hacking attempt!' ); 
}

$weblang = array(
	'yes_1'			=> 'да',
	'yes_2'			=> 'Да',
	'yes_3'			=> 'ДА',

	'no_1'			=> 'нет',
	'no_2'			=> 'Нет',
	'no_3'			=> 'НЕТ',

	'error_title'	=> 'Ошибка!',

);

$field_manager_type = array(
	'1' => array(
		'type'		=> 'input',
		'prefix'	=> 'fi',
		'name'		=> 'Одна строка'

	),
	'2' => array(
		'type'		=> 'textarea',
		'prefix'	=> 'ft',
		'name'		=> 'Несколько строк'

	),
	'3' => array(
		'type'		=> 'select',
		'prefix'	=> 'fs',
		'name'		=> 'Список'

	),
	'4' => array(
		'type'		=> 'checkbox',
		'prefix'	=> 'fc',
		'name'		=> 'Переключатель [Да] или [Нет]',
		'name_min'	=> 'Переключатель'

	),
	'5' => array(
		'type'		=> 'file',
		'prefix'	=> 'ff',
		'name'		=> 'Загрузка изображений и файлов на сервер',
		'dop_name'	=> 'Переместить сюда или выбрать',
		'dop_name1'	=> 'Изображение',
		'dop_name2'	=> 'Изображения',
		'dop_name3'	=> 'Файл',
		'dop_name4'	=> 'Файлы',
		'dop_name5'	=> 'Выберите изображение для загрузки с компьютера',
		'dop_name6'	=> 'Выберите изображения для загрузки с компьютера',
		'dop_name7'	=> 'Выберите файл для загрузки с компьютера',
		'dop_name8'	=> 'Выберите файлы для загрузки с компьютера'

	)

);

$langdate = array(
	'January'		=>	'января',
	'February'		=>	'февраля',
	'March'			=>	'марта',
	'April'			=>	'апреля',
	'May'			=>	'мая',
	'June'			=>	'июня',
	'July'			=>	'июля',
	'August'		=>	'августа',
	'September'		=>	'сентября',
	'October'		=>	'октября',
	'November'		=>	'ноября',
	'December'		=>	'декабря',

	'Jan'		=>	'янв',
	'Feb'		=>	'фев',
	'Mar'		=>	'мар',
	'Apr'		=>	'апр',
	'Jun'		=>	'июн',
	'Jul'		=>	'июл',
	'Aug'		=>	'авг',
	'Sep'		=>	'сен',
	'Oct'		=>	'окт',
	'Nov'		=>	'ноя',
	'Dec'		=>	'дек',

	'Sunday'	=>	'Воскресенье',
	'Monday'	=>	'Понедельник',
	'Tuesday'	=>	'Вторник',
	'Wednesday'	=>	'Среда',
	'Thursday'	=>	'Четверг',
	'Friday'	=>	'Пятница',
	'Saturday'	=>	'Суббота',

	'Sun'	=>	'Вс',
	'Mon'	=>	'Пн',
	'Tue'	=>	'Вт',
	'Wed'	=>	'Ср',
	'Thu'	=>	'Чт',
	'Fri'	=>	'Пт',
	'Sat'	=>	'Сб',
);

$langtimezones = array(
    'Pacific/Midway'       => "(GMT-11:00) Остров Мидуэй",
    'US/Samoa'             => "(GMT-11:00) Самоа",
    'US/Hawaii'            => "(GMT-10:00) Гавайи",
    'US/Alaska'            => "(GMT-09:00) Аляска",
    'US/Pacific'           => "(GMT-08:00) Тихоокеанское время (США и Канада)",
    'America/Tijuana'      => "(GMT-08:00) Тихуана",
    'US/Arizona'           => "(GMT-07:00) Аризона",
    'US/Mountain'          => "(GMT-07:00) Горное время (США и Канада)",
    'America/Chihuahua'    => "(GMT-07:00) Чихуахуа",
    'America/Mazatlan'     => "(GMT-07:00) Масатлан",
    'America/Mexico_City'  => "(GMT-06:00) Мехико",
    'America/Monterrey'    => "(GMT-06:00) Монтеррей",
    'US/Central'           => "(GMT-06:00) Центральное время (США и Канада)",
    'US/Eastern'           => "(GMT-05:00) Восточное время (США и Канада)",
    'US/East-Indiana'      => "(GMT-05:00) Индиана (Восток)",
    'America/Lima'         => "(GMT-05:00) Лима, Богота",
    'America/Caracas'      => "(GMT-04:30) Каракас",
    'Canada/Atlantic'      => "(GMT-04:00) Атлантическое время (Канада)",
    'America/La_Paz'       => "(GMT-04:00) Ла-Пас",
    'America/Santiago'     => "(GMT-04:00) Сантьяго",
    'Canada/Newfoundland'  => "(GMT-03:30) Ньюфаундленд",
    'America/Buenos_Aires' => "(GMT-03:00) Буэнос-Айрес",
    'Greenland'            => "(GMT-03:00) Гренландия",
    'Atlantic/Stanley'     => "(GMT-02:00) Стэнли",
    'Atlantic/Azores'      => "(GMT-01:00) Азорские острова",
    'Africa/Casablanca'    => "(GMT) Касабланка",
    'Europe/Dublin'        => "(GMT) Дублин",
    'Europe/Lisbon'        => "(GMT) Лиссабон",
    'Europe/London'        => "(GMT) Лондон",
    'Europe/Amsterdam'     => "(GMT+01:00) Амстердам",
    'Europe/Belgrade'      => "(GMT+01:00) Белград",
    'Europe/Berlin'        => "(GMT+01:00) Берлин",
    'Europe/Bratislava'    => "(GMT+01:00) Братислава",
    'Europe/Brussels'      => "(GMT+01:00) Брюссель",
    'Europe/Budapest'      => "(GMT+01:00) Будапешт",
    'Europe/Copenhagen'    => "(GMT+01:00) Копенгаген",
    'Europe/Madrid'        => "(GMT+01:00) Мадрид",
    'Europe/Paris'         => "(GMT+01:00) Париж",
    'Europe/Prague'        => "(GMT+01:00) Прага",
    'Europe/Rome'          => "(GMT+01:00) Рим",
    'Europe/Sarajevo'      => "(GMT+01:00) Сараево",
    'Europe/Stockholm'     => "(GMT+01:00) Стокгольм",
    'Europe/Vienna'        => "(GMT+01:00) Вена",
    'Europe/Warsaw'        => "(GMT+01:00) Варшава",
    'Europe/Zagreb'        => "(GMT+01:00) Загреб",
    'Europe/Athens'        => "(GMT+02:00) Афины",
    'Europe/Bucharest'     => "(GMT+02:00) Бухарест",
    'Europe/Helsinki'      => "(GMT+02:00) Хельсинки",
    'Europe/Istanbul'      => "(GMT+02:00) Стамбул",
    'Asia/Jerusalem'       => "(GMT+02:00) Иерусалим",
    'Europe/Kiev'          => "(GMT+02:00) Киев",
    'Europe/Minsk'         => "(GMT+02:00) Минск",
    'Europe/Riga'          => "(GMT+02:00) Рига",
    'Europe/Sofia'         => "(GMT+02:00) София",
    'Europe/Tallinn'       => "(GMT+02:00) Таллин",
    'Europe/Vilnius'       => "(GMT+02:00) Вильнюс",
    'Asia/Baghdad'         => "(GMT+03:00) Багдад",
    'Asia/Kuwait'          => "(GMT+03:00) Кувейт",
    'Africa/Nairobi'       => "(GMT+03:00) Найроби",
    'Asia/Tehran'          => "(GMT+03:30) Иран, Тегеран",
	'Europe/Kaliningrad'   => "(GMT+02:00) Калининград",
    'Europe/Moscow'        => "(GMT+03:00) Москва",
    'Europe/Volgograd'     => "(GMT+03:00) Волгоград",
	'Europe/Samara'        => "(GMT+04:00) Самара, Удмуртия",
	'Asia/Baku'            => "(GMT+04:00) Баку",
    'Asia/Muscat'          => "(GMT+04:00) Абу-Даби, Маскат",
    'Asia/Tbilisi'         => "(GMT+04:00) Тбилиси",
    'Asia/Yerevan'         => "(GMT+04:00) Ереван",
    'Asia/Kabul'           => "(GMT+04:30) Афганистан, Кабул",
    'Asia/Yekaterinburg'   => "(GMT+05:00) Екатеринбург, Пермь",
    'Asia/Tashkent'        => "(GMT+05:00) Ташкент, Карачи",
    'Asia/Kolkata'         => "(GMT+05:30) Бомбей, Калькутта, Мадрас, Нью-Дели, Коломбо",
    'Asia/Kathmandu'       => "(GMT+05:45) Катманду",
    'Asia/Almaty'          => "(GMT+06:00) Алматы, Астана",
    'Asia/Novosibirsk'     => "(GMT+06:00) Новосибирск",
    'Asia/Jakarta'         => "(GMT+07:00) Бангкок, Ханой, Джакарта",
    'Asia/Krasnoyarsk'     => "(GMT+07:00) Красноярск",
    'Asia/Hong_Kong'       => "(GMT+08:00) Гонконг, Чунцин",
    'Asia/Kuala_Lumpur'    => "(GMT+08:00) Куала-Лумпур",
    'Asia/Singapore'       => "(GMT+08:00) Сингапур",
    'Asia/Taipei'          => "(GMT+08:00) Тайбэй",
    'Asia/Ulaanbaatar'     => "(GMT+08:00) Улан-Батор",
    'Asia/Urumqi'          => "(GMT+08:00) Урумчи",
    'Asia/Irkutsk'         => "(GMT+08:00) Иркутск",
    'Asia/Seoul'           => "(GMT+09:00) Сеул",
    'Asia/Tokyo'           => "(GMT+09:00) Токио, Осака, Саппоро",
    'Australia/Adelaide'   => "(GMT+09:30) Аделаида",
    'Australia/Darwin'     => "(GMT+09:30) Дарвин",
    'Asia/Yakutsk'         => "(GMT+09:00) Якутск",
    'Australia/Brisbane'   => "(GMT+10:00) Брисбен",
    'Pacific/Port_Moresby' => "(GMT+10:00) Гуам, Порт-Морсби",
    'Australia/Sydney'     => "(GMT+10:00) Мельбурн, Сидней, Канберра",
    'Asia/Vladivostok'     => "(GMT+10:00) Владивосток",
    'Asia/Sakhalin'    	   => "(GMT+11:00) Сахалин",
    'Asia/Magadan'         => "(GMT+12:00) Магадан, Камчатка",
    'Pacific/Auckland'     => "(GMT+12:00) Окленд, Веллингтон",
    'Pacific/Fiji'         => "(GMT+12:00) Фиджи, Маршалловы о.",
);

$langtranslit = array(
	'а' => 'a', 'б' => 'b', 'в' => 'v',
	'г' => 'g', 'д' => 'd', 'е' => 'e',
	'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
	'и' => 'i', 'й' => 'y', 'к' => 'k',
	'л' => 'l', 'м' => 'm', 'н' => 'n',
	'о' => 'o', 'п' => 'p', 'р' => 'r',
	'с' => 's', 'т' => 't', 'у' => 'u',
	'ф' => 'f', 'х' => 'h', 'ц' => 'c',
	'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
	'ь' => '', 'ы' => 'y', 'ъ' => '',
	'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
	'ї' => 'yi', 'є' => 'ye',

	'А' => 'A', 'Б' => 'B', 'В' => 'V',
	'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
	'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
	'И' => 'I', 'Й' => 'Y', 'К' => 'K',
	'Л' => 'L', 'М' => 'M', 'Н' => 'N',
	'О' => 'O', 'П' => 'P', 'Р' => 'R',
	'С' => 'S', 'Т' => 'T', 'У' => 'U',
	'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
	'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
	'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
	'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
	'Ї' => 'yi', 'Є' => 'ye', 
	'À'=>'A', 'à'=>'a', 'Á'=>'A', 'á'=>'a', 
	'Â'=>'A', 'â'=>'a', 'Ä'=>'A', 'ä'=>'a', 
	'Ã'=>'A', 'ã'=>'a', 'Å'=>'A', 'å'=>'a', 
	'Æ'=>'AE', 'æ'=>'ae', 'Ç'=>'C', 'ç'=>'c', 
	'Ð'=>'D', 'È'=>'E', 'è'=>'e', 'É'=>'E', 
	'é'=>'e', 'Ê'=>'E', 'ê'=>'e', 'Ì'=>'I', 
	'ì'=>'i', 'Í'=>'I', 'í'=>'i', 'Î'=>'I', 
	'î'=>'i', 'Ï'=>'I', 'ï'=>'i', 'Ñ'=>'N', 
	'ñ'=>'n', 'Ò'=>'O', 'ò'=>'o', 'Ó'=>'O', 
	'ó'=>'o', 'Ô'=>'O', 'ô'=>'o', 'Ö'=>'O', 
	'ö'=>'o', 'Õ'=>'O', 'õ'=>'o', 'Ø'=>'O', 
	'ø'=>'o', 'Œ'=>'OE', 'œ'=>'oe', 'Š'=>'S', 
	'š'=>'s', 'Ù'=>'U', 'ù'=>'u', 'Û'=>'U', 
	'û'=>'u', 'Ú'=>'U', 'ú'=>'u', 'Ü'=>'U', 
	'ü'=>'u', 'Ý'=>'Y', 'ý'=>'y', 'Ÿ'=>'Y', 
	'ÿ'=>'y', 'Ž'=>'Z', 'ž'=>'z', 'Þ'=>'B', 
	'þ'=>'b', 'ß'=>'ss', '£'=>'pf', '¥'=>'ien', 
	'ð'=>'eth', 'ѓ'=>'r'
);


?>
