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
| Файл: modules.php
-----------------------------------------------------
| Назначение: подключение дополнительных модулей
=====================================================
*/

if ( ! defined ( 'CATHARSIS_SHOP' ) ) { 
	die ( "Hacking attempt!" ); 

}

$title_a = FALSE;

switch ( $do ) {
	case 'xxx' :
		include SYSTEM_DIR . '/modules/xxx.php';
		$js_files_add[] = '<script type="text/javascript" src="' . $config['http_home_url'] . 'system/skins/js/script.js"></script>';
		break;

}

if ( $title_a ) {
	$metatags['title'] = $metatags['title'] . ' ' . $config['separator'] . ' ' . $title_a;

}

$metatags = <<<HTML
<meta http-equiv="Content-Type" content="text/html; charset={$config['charset']}" />
		<title>{$metatags['title']}</title>
		<meta name="description" content="{$metatags['description']}" />
		<meta name="keywords" content="{$metatags['keywords']}" />
		<meta name="generator" content="CMS CATHARSIS SHOP - by D0Gmatist (http://d0gmatist.pro/)" />{$s_meta}
HTML;

/* speedbar */
$speedbar = array();
if ( $do != 'main' ) {
	$speedbar[] = '<li><a href="' . $config['http_home_url'] . '">' . $config['spidbar_title'] . '</a></li>';

} else {
	$speedbar[] = '<li class="active">' . $config['spidbar_title'] . '</li>';

}

if ( $title_a ) {
	$speedbar[] = '<li class="active">' . $title_a . '</li>';

}

$tpl->load_template ( 'speedbar.tpl' );
$speedbar = implode( '', $speedbar );
$tpl->set ( '{speedbar}', '<ul class="breadcrumb" id="speedbar">' . stripslashes ( $speedbar ) . '</ul>' );

$tpl->compile ( 'speedbar' );
$tpl->clear ();

?>
