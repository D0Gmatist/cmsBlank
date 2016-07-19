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
| Файл: init.php
-----------------------------------------------------
| Назначение: оснавная система
=====================================================
*/

if ( ! defined ( 'CATHARSIS_SHOP' ) ) { 
	die ( "Hacking attempt!" ); 

}

// Конфиг сайта
include SYSTEM_DIR . '/configs/config.php';

date_default_timezone_set ( $config['date_adjust'] );

// CLASS запроса к бд
require_once SYSTEM_DIR . '/classes/mysql.class.php';
// Конфиг подключения к бд
require_once SYSTEM_DIR . '/configs/dbconfig.php';
// Основные functions
require_once SYSTEM_DIR . '/functions/functions.php';

############################## catalog
$cat_info = get_vars( 'catalog' );

if ( ! is_array( $cat_info ) ) {
	$cat_info = array();

	$db->query( "SELECT 
						* 
							FROM 
								" . PREFIX . "_catalog 
													ORDER BY 
														`cat_position` 
															ASC" );
	while ( $row = $db->get_row() ) {
		$cat_info[$row['cat_id']] = array();

		foreach ( $row as $key => $value ) {
			$cat_info[$row['cat_id']][$key] = stripslashes( $value );

		}

	}
	set_vars( 'catalog', $cat_info );
	$db->free();

}

############################## field_manager
$field_manager_info = get_vars( 'field_manager' );

if ( ! is_array( $field_manager_info ) ) {
	$field_manager_info = array();

	$db->query( "SELECT 
						* 
							FROM 
								" . PREFIX . "_field_manager 
													ORDER BY 
														`fm_id` 
															ASC" );
	while ( $row = $db->get_row() ) {
		$field_manager_info[$row['fm_id']] = array();

		foreach ( $row as $key => $value ) {
			$field_manager_info[$row['fm_id']][$key] = stripslashes( $value );

		}

	}
	set_vars( 'field_manager', $field_manager_info );
	$db->free();

}

// запуск сессии
catharsis_session();
check_xss();

$Timer = new microTimer();
$member_id = FALSE;
$is_logged = FALSE;

$msgbox_status = TRUE;
$msgbox_text = array();

$_TIME = time();
$datetime = date( 'Y-m-d H:i:s', $_TIME );

$config['charset'] = strtolower( $config['charset'] );

$metatags = array (
				'title'			=> $config['home_title'],
				'description'	=> $config['description'],
				'keywords'		=> $config['keywords'],
				'header_title'	=> ''
			);

// язики
include_once ROOT_DIR . '/language/' . $config['langs'] . '/site.lng';

// Постраничная навигация
$cstart = (int)$_GET['cstart'];
if ( $cstart < 1 ) {
	$cstart = 0; 

}

$do = $_GET['do'];
if ( ! $do ) {
	$do = 'main';

}

// CLASS для работы с шаблонами
require_once SYSTEM_DIR . '/classes/costume.class.php';

// старт шаблон
$tpl = new shop_template();
$tpl->dir = ROOT_DIR . '/templates/' . $config['skin'];
define ( 'CATHARSIS_SHOP_DIR', $tpl->dir );

// проверка разрешения на авторизацию
include_once SYSTEM_DIR . '/modules/sitelogin.php';
if ( ! $is_logged ) {
	$member_id['us_group'] = 5;

}

// шаблон авторизации и пользователя
require_once SYSTEM_DIR . '/modules/login_panel.php';

// site = online & offline
/*if ( $config['site_offline'] == "yes" ) {
	include_once SYSTEM_DIR . '/modules/offline.php';
}*/

// модули
require_once SYSTEM_DIR . '/modules.php';

// вывод
require_once SYSTEM_DIR . '/modules/main.php';

?>
