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
| Файл: functions.php
-----------------------------------------------------
| Назначение: Основные функции
=====================================================
*/

if ( ! defined ( 'CATHARSIS_SHOP' ) ) { 
	die ( 'Hacking attempt!' ); 
}

require_once SYSTEM_DIR . '/arrays/arrays.php';

if ( $config['auth_domain'] ) {
	$domain_cookie = explode ( ".", clean_url( $_SERVER['HTTP_HOST'] ) );
	$domain_cookie_count = count( $domain_cookie );
	$domain_allow_count = -2;

	if ( $domain_cookie_count > 2 ) {
		if ( in_array($domain_cookie[$domain_cookie_count-2], array('com', 'net', 'org') )) {
			$domain_allow_count = -3;

		}

		if ( $domain_cookie[$domain_cookie_count-1] == 'ua' ) {
			$domain_allow_count = -3;

		}
		$domain_cookie = array_slice( $domain_cookie, $domain_allow_count );

	}
	$domain_cookie = '.' . implode ( '.', $domain_cookie );

	if( ( ip2long( $_SERVER['HTTP_HOST'] ) == -1 OR ip2long( $_SERVER['HTTP_HOST'] ) === FALSE ) AND strtoupper( substr( PHP_OS, 0, 3) ) !== 'WIN' ) {
		define( 'DOMAIN', $domain_cookie );

	}else {
		define( 'DOMAIN', null );

	}

} else {
	define( 'DOMAIN', null );

}

function catharsis_session( $sid = FALSE ) {
	$params = session_get_cookie_params();

	if ( DOMAIN ) {
		$params['domain'] = DOMAIN;

	}

	if ( version_compare( PHP_VERSION, '5.2', '<' ) ) {
		session_set_cookie_params( $params['lifetime'], '/', $params['domain'] . '; HttpOnly', $params['secure'] );

	} else {
		session_set_cookie_params( $params['lifetime'], '/', $params['domain'], $params['secure'], TRUE );

	}

	if ( $sid ) {
		@session_id( $sid );

	}
	@session_start();

}

function set_cookie( $name, $value, $expires ) {
	if( $expires ) {
		$expires = time() + ( $expires * 86400 );

	} else {
		$expires = FALSE;

	}

	setcookie( $name, $value, $expires, '/', DOMAIN, NULL, TRUE );

}

function formatsize( $file_size ) {
	
	if ( ! $file_size OR $file_size < 1 ) {
		return '0 b';

	}

    $prefix = array( 'b', 'Kb', 'Mb', 'Gb', 'Tb' );
    $exp = floor( log( $file_size, 1024 ) ) | 0;

    return round( $file_size / ( pow( 1024, $exp ) ), 2) . ' ' . $prefix[$exp];

}

class microTimer {
	var $time;

	function __construct() {
		$this->time = $this->get_real_time();

	}

	function get() {
		return round( ( $this->get_real_time() - $this->time ), 5 );

	}

	function get_real_time() {
		list ( $seconds, $microSeconds ) = explode( ' ', microtime() );
		return ( ( float ) $seconds + ( float ) $microSeconds );

	}

}

function totranslit( $var, $lower = TRUE, $punkt = TRUE ) {
	global $langtranslit;
	
	if ( is_array( $var ) ) {
		return '';

	}
	$var = str_replace(chr(0), '', $var);

	if ( ! is_array ( $langtranslit ) OR ! count( $langtranslit ) ) {
		$var = trim( strip_tags( $var ) );

		if ( $punkt ) {
			$var = preg_replace( "/[^a-z0-9\_\-.]+/mi", '', $var );

		} else {
			$var = preg_replace( "/[^a-z0-9\_\-]+/mi", '', $var );

		}
		$var = preg_replace( '#[.]+#i', '.', $var );
		$var = str_ireplace( ".php", ".ppp", $var );

		if ( $lower ) {
			$var = strtolower( $var );

		}
		return $var;

	}
	$var = trim( strip_tags( $var ) );
	$var = preg_replace( "/\s+/ms", '-', $var );
	$var = str_replace( '/', '-', $var );
	$var = strtr($var, $langtranslit);

	if ( $punkt ) {
		$var = preg_replace( "/[^a-z0-9\_\-.]+/mi", '', $var );

	} else {
		$var = preg_replace( "/[^a-z0-9\_\-]+/mi", '', $var );

	}
	$var = preg_replace( '#[\-]+#i', '-', $var );
	$var = preg_replace( '#[.]+#i', '.', $var );

	if ( $lower ) {
		$var = strtolower( $var );

	}
	$var = str_ireplace( '.php', '', $var );
	$var = str_ireplace( '.php', '.ppp', $var );

	if ( strlen( $var ) > 200 ) {
		$var = substr( $var, 0, 200 );

		if ( ( $temp_max = strrpos( $var, '-' ) ) ) {
			$var = substr( $var, 0, $temp_max );

		}

	}
	return $var;

}

function langdate( $format, $stamp, $servertime = FALSE, $custom = FALSE ) {
	global $langdate, $member_id, $customlangdate;

	$timezones = array('Pacific/Midway','US/Samoa','US/Hawaii','US/Alaska','US/Pacific','America/Tijuana','US/Arizona','US/Mountain','America/Chihuahua','America/Mazatlan','America/Mexico_City','America/Monterrey','US/Central','US/Eastern','US/East-Indiana','America/Lima','America/Caracas','Canada/Atlantic','America/La_Paz','America/Santiago','Canada/Newfoundland','America/Buenos_Aires','Greenland','Atlantic/Stanley','Atlantic/Azores','Africa/Casablanca','Europe/Dublin','Europe/Lisbon','Europe/London','Europe/Amsterdam','Europe/Belgrade','Europe/Berlin','Europe/Bratislava','Europe/Brussels','Europe/Budapest','Europe/Copenhagen','Europe/Madrid','Europe/Paris','Europe/Prague','Europe/Rome','Europe/Sarajevo','Europe/Stockholm','Europe/Vienna','Europe/Warsaw','Europe/Zagreb','Europe/Athens','Europe/Bucharest','Europe/Helsinki','Europe/Istanbul','Asia/Jerusalem','Europe/Kiev','Europe/Minsk','Europe/Riga','Europe/Sofia','Europe/Tallinn','Europe/Vilnius','Asia/Baghdad','Asia/Kuwait','Africa/Nairobi','Asia/Tehran','Europe/Kaliningrad','Europe/Moscow','Europe/Volgograd','Europe/Samara','Asia/Baku','Asia/Muscat','Asia/Tbilisi','Asia/Yerevan','Asia/Kabul','Asia/Yekaterinburg','Asia/Tashkent','Asia/Kolkata','Asia/Kathmandu','Asia/Almaty','Asia/Novosibirsk','Asia/Jakarta','Asia/Krasnoyarsk','Asia/Hong_Kong','Asia/Kuala_Lumpur','Asia/Singapore','Asia/Taipei','Asia/Ulaanbaatar','Asia/Urumqi','Asia/Irkutsk','Asia/Seoul','Asia/Tokyo','Australia/Adelaide','Australia/Darwin','Asia/Yakutsk','Australia/Brisbane','Pacific/Port_Moresby','Australia/Sydney','Asia/Vladivostok','Asia/Sakhalin','Asia/Magadan','Pacific/Auckland','Pacific/Fiji');

	if( is_array( $custom ) ) {
		$locallangdate = $customlangdate;

	} else {
		$locallangdate = $langdate;

	}

	if ( ! $stamp ) {
		$stamp = time();
	
	}
	$local = new DateTime( '@' . $stamp );

	if ( isset( $member_id['timezone'] ) AND $member_id['timezone'] AND ! $servertime ) {
		$localzone = $member_id['timezone'];

	} else {
		$localzone = date_default_timezone_get();

	}

	if ( ! in_array( $localzone, $timezones ) ) {
		$localzone = 'Europe/Moscow';

	}
	$local->setTimeZone( new DateTimeZone( $localzone ) );

	return strtr( $local->format( $format ), $locallangdate );

}

function formdate( $matches = array() ) {
	global $news_date, $customlangdate;
	return langdate( $matches[1], $news_date, FALSE, $customlangdate );

}

function msgbox( $title, $text, $type ) {
	global $tpl;

	if ( ! class_exists( 'shop_template' ) ) {
	    return;

	}

	$tpl_2 = new shop_template( );
	$tpl_2->dir = CATHARSIS_SHOP_DIR;

	if ( $type == 'good' ) {
		$tpl_name = 'info_good';

	} else if ( $type == 'bad' ) {
		$tpl_name = 'info_bad';

	} else {
		$tpl_name = 'info';

	}
	$tpl_2->load_template( 'info/' . $tpl_name . '.tpl' );
	
	$tpl_2->set( '{text}', $text );
	$tpl_2->set( '{title}', $title );
	
	$tpl_2->compile( 'info' );
	$tpl_2->clear();
	
	$tpl->result['info'] .= $tpl_2->result['info'];

}

function set_vars( $file, $data ) {
	if ( is_array( $data ) OR is_int( $data ) ) {
		$file = totranslit($file, TRUE, FALSE );	
		$fp = fopen( SYSTEM_DIR . '/cache/system/' . $file . '.php', 'wb+' );
		fwrite( $fp, serialize( $data ) );
		fclose( $fp );

		@chmod( SYSTEM_DIR . '/cache/system/' . $file . '.php', 0666 );

	}

}

function get_vars( $file ) {
	$file = totranslit( $file, TRUE, FALSE );
	$data = @file_get_contents( SYSTEM_DIR . '/cache/system/' . $file . '.php' );

	if ( $data !== FALSE ) {
		$data = unserialize( $data );

		if ( is_array( $data ) OR is_int( $data ) ) {
			return $data;

		}

	} 
	return FALSE;	

}

function open_cache( $prefix, $cache_id = FALSE, $member_prefix = FALSE ) {
	global $config, $is_logged, $member_id;
	
	if( ! $config['allow_cache'] ) {
		return FALSE;

	}
	$config['clear_cache'] = ( intval($config['clear_cache'] ) > 1 ) ? intval( $config['clear_cache'] ) : 0;

	if( $is_logged ) {
		$end_file = $member_id['user_group'];

	} else {
		$end_file = 0;

	}
	
	if( ! $cache_id ) {
		$key = $prefix;
	
	} else {
		$cache_id = md5( $cache_id );
		
		if( $member_prefix ) {
			$key = $prefix . '_' . $cache_id . '_' . $end_file;

		} else {
			$key = $prefix . '_' . $cache_id;

		}
	
	}
	$buffer = @file_get_contents( SYSTEM_DIR . '/cache/' . $key . '.tmp' );

	if ( $buffer !== FALSE AND $config['clear_cache'] ) {
		$file_date = @filemtime( SYSTEM_DIR . '/cache/' . $key . '.tmp' );
		$file_date = time() - $file_date;

		if ( $file_date > ( $config['clear_cache'] * 60 ) ) {
			$buffer = FALSE;
			@unlink( SYSTEM_DIR . '/cache/' . $key . '.tmp' );

		}
		return $buffer;

	} else {
		return $buffer;

	}

}

function create_cache( $prefix, $cache_text, $cache_id = FALSE, $member_prefix = FALSE ) {
	global $config, $is_logged, $member_id;

	if( ! $config['allow_cache'] ) {
		return FALSE;

	}

	if( $is_logged ) {
		$end_file = $member_id['user_group'];

	} else {
		$end_file = 0;

	}

	if( ! $cache_id ) {
		$key = $prefix;

	} else {
		$cache_id = md5( $cache_id );

		if( $member_prefix ) {
			$key = $prefix . '_' . $cache_id . '_' . $end_file;

		} else {
			$key = $prefix . '_' . $cache_id;

		}

	}
	file_put_contents ( SYSTEM_DIR . '/cache/' . $key . '.tmp', $cache_text, LOCK_EX );

	@chmod( SYSTEM_DIR . '/cache/' . $key . '.tmp', 0666 );

}

function clear_cache( $cache_areas = FALSE ) {
	if ( $cache_areas ) {
		if( ! is_array( $cache_areas ) ) {
			$cache_areas = array( $cache_areas );

		}

	}
	$fdir = opendir( SYSTEM_DIR . '/cache' );

	while ( $file = readdir( $fdir ) ) {
		if( $file != '.' and $file != '..' and $file != '.htaccess' and $file != 'system' ) {
			if( $cache_areas ) {
				foreach( $cache_areas as $cache_area ) {
					if( strpos( $file, $cache_area ) !== FALSE ) {
						@unlink( SYSTEM_DIR . '/cache/' . $file );

					}

				}

			} else {
				@unlink( SYSTEM_DIR . '/cache/' . $file );

			}

		}

	}

}

function keyword( $story ) {
	global $config;

	$keyword_count = 20;
	$newarr = array ();
	$quotes = array ("\x27", "\x22", "\x60", "\t", "\n", "\r", ",", ".", "/", "\\", "¬", "#", ";", ":", "@", "~", "[", "]", "{", "}", "=", "-", "+", ")", "(", "*", "^", "%", "$", "<", ">", "?", "!", '"');
	$fastquotes = array ("\x27", "\x22", "\x60", "\t", "\n", "\r", '"', "\\", '\r', '\n', "/", "{", "}", "[", "]" );

	$story = str_replace( "&nbsp;", ' ', $story );
	$story = str_replace( '<br />', ' ', $story );
	$story = strip_tags( $story );
	$story = preg_replace( "#&(.+?);#", '', $story );
	$story = trim(str_replace( ' ,', '', stripslashes( $story )));
	$story = str_replace( $fastquotes, '', $story );

	$story = str_replace( $quotes, ' ', $story );
	$story = explode( ' ', $story );

	foreach ( $story as $word ) {
		if( shop_strlen( $word, $config['charset'] ) >= 4 ) {
			$newarr[] = $word;

		}

	}
	$story = array_count_values( $newarr );
	$story = array_keys( $story );

	$story = array_slice( $story, 0, $keyword_count );
	$story = implode( ', ', $story );

    return $story;

}

function create_metatags( $story ) {
	global $metatags, $config;

	$keyword_count = 20;
	$newarr = array ();
	$quotes = array ("\x27", "\x22", "\x60", "\t", "\n", "\r", ",", ".", "/", "\\", "¬", "#", ";", ":", "@", "~", "[", "]", "{", "}", "=", "-", "+", ")", "(", "*", "^", "%", "$", "<", ">", "?", "!", '"');
	$fastquotes = array ("\x27", "\x22", "\x60", "\t", "\n", "\r", '"', "\\", '\r', '\n', "/", "{", "}", "[", "]" );

	$story = str_replace( "&nbsp;", ' ', $story );
	$story = str_replace( '<br />', ' ', $story );
	$story = strip_tags( $story );
	$story = preg_replace( "#&(.+?);#", '', $story );
	$story = trim(str_replace( ' ,', '', stripslashes( $story )));
	$story = str_replace( $fastquotes, '', $story );

	$metatags['description'] = shop_substr( $story, 0, 200, $config['charset'] );

	if( ( $temp_dmax = shop_strrpos( $metatags['description'], ' ', $config['charset'] ) ) ) {
		$metatags['description'] = shop_substr( $metatags['description'], 0, $temp_dmax, $config['charset'] );

	}
	$story = str_replace( $quotes, ' ', $story );
	$arr = explode( ' ', $story );

	foreach ( $arr as $word ) {
		if( shop_strlen( $word, $config['charset'] ) > 4 ) {
			$newarr[] = $word;

		}

	}
	$arr = array_count_values( $newarr );
	arsort( $arr );
	$arr = array_keys( $arr );

	$arr = array_slice( $arr, 0, $keyword_count );
	$metatags['keywords'] = implode( ', ', $arr );

}

function check_xss() {
	$url = html_entity_decode( urldecode( $_SERVER['QUERY_STRING'] ), ENT_QUOTES, 'ISO-8859-1' );
	$url = str_replace( "\\", "/", $url );

	$url = html_entity_decode( urldecode( $_SERVER['REQUEST_URI'] ), ENT_QUOTES, 'ISO-8859-1' );
	$url = str_replace( "\\", "/", $url );

	if( $url ) {
		if( ( strpos( $url, '<' ) !== FALSE ) || ( strpos( $url, '>' ) !== FALSE ) || ( strpos( $url, '\'' ) !== FALSE ) ) {
			if( $_GET['do'] != "search" OR $_GET['subaction'] != "search" ) {
				die( 'Hacking attempt!' );

			}

		}

	}

}

function clean_url( $url ) {
	if( $url == '' ) {
		return;

	}

	$url = str_replace( 'http://', '', strtolower( $url ) );
	$url = str_replace( 'https://', '', $url );
	if ( substr( $url, 0, 2 ) == '//' ) {
		$url = str_replace( "//", '', $url );

	}

	if ( substr( $url, 0, 4 ) == 'www.' ) {
		$url = substr( $url, 4 );

	}
	$url = explode( '/', $url );
	$url = reset( $url );
	$url = explode( ':', $url );
	$url = reset( $url );

	return $url;

}

function convert_unicode( $t, $to = 'windows-1251' ) {
	$to = strtolower( $to );

	if ( $to == 'utf-8' ) {
		return $t;

	} else {
		if( function_exists( 'mb_convert_encoding' ) ) {
			$t = mb_convert_encoding( $t, $to, 'UTF-8' );

		} else if ( function_exists( 'iconv' ) ) {
			$t = iconv( "UTF-8", $to . "//IGNORE", $t );

		} else {
			$t = 'iconv и mbstring библиотеки не поддерживаются сервером';

		}

	}
	return $t;

}

function shop_strlen( $value, $charset ) {
	if ( strtolower( $charset ) == 'utf-8' ) {
		if ( function_exists( 'mb_strlen' ) ) {
			return mb_strlen( $value, 'utf-8' );

		} else if ( function_exists( 'iconv_strlen' ) ) {
			return iconv_strlen( $value, 'utf-8' );

		}

	}
	return strlen( $value );

}

function shop_substr($str, $start, $length, $charset ) {
	if ( strtolower( $charset ) == 'utf-8') {
		if( function_exists( 'mb_substr' ) ) {
			return mb_substr( $str, $start, $length, 'utf-8' );

		} else if ( function_exists( 'iconv_substr' ) ) {
			return iconv_substr( $str, $start, $length, 'utf-8' );

		}

	}
	return substr( $str, $start, $length );

}

function shop_strrpos( $str, $needle, $charset ) {
	if ( strtolower( $charset ) == 'utf-8' ) {
		if ( function_exists( 'mb_strrpos' ) ) {
			return mb_strrpos( $str, $needle, null, 'utf-8' );

		} else if ( function_exists( 'iconv_strrpos' ) ) {
			return iconv_strrpos($str, $needle, 'utf-8' );

		}

	}
	return strrpos( $str, $needle );

}

function detect_encoding( $string ) {  
	static $list = array( 'utf-8', 'windows-1251' );
   
	foreach ( $list as $item ) {
		if( function_exists( 'mb_convert_encoding' ) ) {
			$sample = mb_convert_encoding( $string, $item, $item );

		} else if ( function_exists( 'iconv' ) ) {
			$sample = iconv( $item, $item, $string );

		}

		if ( md5( $sample ) == md5( $string ) ) {
			return $item;

		}

	}
	return null;

}

function get_ip() {
	if ( filter_var( $_SERVER['REMOTE_ADDR'] , FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
		return filter_var( $_SERVER['REMOTE_ADDR'] , FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );

	}

	if ( filter_var( $_SERVER['REMOTE_ADDR'] , FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) ) {
		return filter_var( $_SERVER['REMOTE_ADDR'] , FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 );

	}
	return 'localhost';

}

function CheckGzip(){ 
	if ( headers_sent() || connection_aborted() || !function_exists( 'ob_gzhandler' ) || ini_get( 'zlib.output_compression' ) ) {
		return 0;

	}

	if ( strpos( $_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip' ) !== FALSE ) {
		return 'x-gzip';

	}

	if ( strpos( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) !== FALSE ) {
		return 'gzip';
	
	}
	return 0; 

}

function GzipOut( $debug = 0 ) {
	global $config, $Timer, $db, $tpl, $_DOCUMENT_DATE;

	$s = '';

	@header( 'Content-type: text/html; charset=' . $config['charset'] );

	if ( $debug ) {
		$s = "\n<!-- Время выполнения скрипта " . $Timer->get() . " секунд -->\n<!-- Время затраченное на компиляцию шаблонов " . round( $tpl->template_parse_time, 5 ) . " секунд -->\n<!-- Время затраченное на выполнение MySQL запросов: " . round( $db->MySQL_time_taken, 5 ) . " секунд -->\n<!-- Общее количество MySQL запросов " . $db->query_num." -->";

	}

	if( $debug AND function_exists( 'memory_get_peak_usage' ) ) {
		$s .= "\n<!-- Затрачено оперативной памяти " . round( memory_get_peak_usage() / ( 1024 * 1024 ), 2 ) . " MB -->";

	}

	if( $_DOCUMENT_DATE ) {
		@header ( 'Last-Modified: ' . date( 'r', $_DOCUMENT_DATE ) . ' GMT' );

	}

	if ( ! $config['allow_gzip'] ) {
		if ( $debug ) {
			echo $s;
			ob_end_flush();
			return;

		}

	}
    $ENCODING = CheckGzip();

    if ( $ENCODING ) {
        $s .= "\n<!-- Для вывода использовалось сжатие $ENCODING -->\n";
        $Contents = ob_get_clean();

        if ( $debug ){
            $s .= "<!-- Общий размер файла: " . strlen( $Contents ) . " байт ";
            $s .= "После сжатия: " . strlen( gzencode( $Contents, 1, FORCE_GZIP ) ) . " байт -->";
            $Contents .= $s;

        }
        header( 'Content-Encoding: ' . $ENCODING );


		$Contents = gzencode( $Contents, 1, FORCE_GZIP );
		echo $Contents;
		ob_end_flush();
        exit; 

    } else {
        ob_end_flush(); 
        exit; 

    }

}

// nestable админка категории
function DisplayCatalogs( $parent_id = 0, $sublevelmarker = FALSE ) {
	global $cat_info, $config;

	$cat_item = '';

	if ( count( $cat_info ) ) {
		foreach ( $cat_info AS $cats ) {
			if ( $cats['cat_parent_id'] == $parent_id ) {
				$root_catalogs[] = $cats['cat_id'];

			}

		}

		if ( count( $root_catalogs ) ) {
			foreach ( $root_catalogs AS $cat_id ) {
				$link = '<a href="' . $config['http_home_url'] . 'catalog/' .  $cat_info[$cat_id]['cat_alt_name'] . '/" target="_blank">' . stripslashes( $cat_info[$cat_id]['cat_name'] ) . '</a>';

				if ( $cat_info[$cat_id]['cat_description'] ) {
					$meta_info = '<b class="nestable_info active" data-toggle="tooltip" data-placement="top" title="' . $cat_info[$cat_id]['cat_description'] . '">D</b>';

				} else {
					$meta_info = '<b class="nestable_info">D</b>';

				}

				if ( $cat_info[$cat_id]['cat_keywords'] ) {
					$meta_info .= '<b class="nestable_info active" data-toggle="tooltip" data-placement="top" title="' . $cat_info[$cat_id]['cat_keywords'] . '">K</b>';

				} else {
					$meta_info .= '<b class="nestable_info">K</b>';

				}

				$cat_item .= <<<HTML
					<li class="dd-item" data-cat_id="{$cat_info[$cat_id]['cat_id']}">
						<div class="dd-handle">
							<b>ID:{$cat_info[$cat_id]['cat_id']}</b>
							{$link} 
							<div class="pull-right">
								<div class="nestable_info_form">{$meta_info}</div>
								<a class="pull-right-btn-mdi" href="{$config['http_home_url']}catalog_managing/cat_id-{$cat_info[$cat_id]['cat_id']}/"><i class="mdi mdi-lead-pencil"></i></a>
								<div class="pull-right-btn-mdi" data-btn="cat_delete" data-cat_id="{$cat_info[$cat_id]['cat_id']}"><i class="mdi mdi-delete"></i></div>
							</div>
						</div>
HTML;

				$cat_item .= DisplayCatalogs( $cat_id, TRUE );

				$cat_item .= '</li>';

			}

			if ( $sublevelmarker ) {
				return '<ol class="dd-list">' . $cat_item . '</ol>';

			} else {
				return $cat_item;

			}

		}

	} else {
		return '<li><div class="text-center text-info">Каталоги ещё не создавались</div></li>';

	}

}

// Генерация меню-каталога
function DisplayCatalogsMenu( $parent_id = 0, $sublevelmarker = FALSE, $level = 1 ) {
	global $cat_info, $config;

	$return = '';

	if ( count( $cat_info ) ) {
		foreach ( $cat_info as $cats ) {
			if ( $cats['cat_parent_id'] == $parent_id ) {
				$root_catalogs[] = $cats['cat_id'];

			}

		}

		switch( $level ) {
			case 1 :
					$css1 = 'light-marengo1';
					$css2 = 'marengo1';
					$mdi = 'menu';
				break;

			case 2 :
					$css1 = 'light-marengo2';
					$css2 = 'marengo2';
					$mdi = 'chevron-right';
				break;

			case 3 :
					$css1 = 'light-marengo3';
					$css2 = 'marengo3';
				break;

			default :
					$css1 = 'light-marengo1';
					$css2 = 'marengo1';

		}
		$pod_level = $level + 1;

		if ( count( $root_catalogs ) ) {
			foreach ( $root_catalogs as $cat_id ) {
				$pod_cat_item = DisplayCatalogsMenu( $cat_id, TRUE, $pod_level );

				$icon = '';
				if ( $pod_cat_item ) {
					$icon = '<div data-btn="menu" data-level="' . $level . '" data-cat_id="' . $cat_id . '" class="btn btn-' . $css2 . ' btn-sm"><i class="mdi mdi-' . $mdi . '"></i></div>';

				}

				$name = stripslashes( $cat_info[$cat_id]['cat_name'] );
				$return .= <<<HTML
				<li>
					<div class="btn-group">{$icon}<a class="btn btn-{$css1} btn-sm" href="{$config['http_home_url']}catalog/{$cat_info[$cat_id]['cat_alt_name']}/"><span>{$name}</span></a></div>
					{$pod_cat_item}
				</li>
HTML;

			}

			if ( $sublevelmarker ) {
				return '<ul data-menu_content="' . $parent_id . '" style="display:none;">' . $return . '</ul>';

			} else {
				$return = <<<HTML
        <div class="catalog_menu">
            <ul>
				{$return}
            </ul>
        </div>
HTML;
				return $return;

			}

		}

	}

}

// array категорий для спид бара
function SpidbarCatalogs( $return = array(), $cat_id, $step = 1, $parent = FALSE ) {
	global $cat_info;

	$return[$step] = array();
	$return[$step]['cat_id'] = $cat_info[$cat_id]['cat_id'];
	$return[$step]['cat_name'] = $cat_info[$cat_id]['cat_name'];
	$return[$step]['cat_alt_name'] = $cat_info[$cat_id]['cat_alt_name'];
	$return[$step]['cat_description'] = $cat_info[$cat_id]['cat_description'];
	$return[$step]['cat_keywords'] = $cat_info[$cat_id]['cat_keywords'];

	if ( $cat_info[$cat_id]['cat_parent_id'] > 0 ) {
		$parent_step = $step + 1;
		$return = SpidbarCatalogs( $return, $cat_info[$cat_id]['cat_parent_id'], $parent_step, $parent = FALSE );

	}
	return $return;

}

// разбор JSON array
function ParseJsonArray( $json_array, $cat_parent_id = 0 ) {
	$return = array();

	foreach ( $json_array as $sub_array ) {
		$return_sub_sub_array = array();
		if ( isset( $sub_array['children'] ) ) {
			$return_sub_sub_array = ParseJsonArray( $sub_array['children'], $sub_array['cat_id'] );

		}
		$return[] = array( 'cat_id' => $sub_array['cat_id'], 'cat_parent_id' => $cat_parent_id );
		$return = array_merge( $return, $return_sub_sub_array );

	}
	return $return;

}

// select каталог
function SelectFieldManagerCategories( $name, $action = array(), $parent_id = 0, $level = 0 ) {
	global $cat_info, $config;

	$return = '';

	if ( count( $cat_info ) ) {
		foreach ( $cat_info AS $cats ) {
			if ( $cats['cat_parent_id'] == $parent_id ) {
				$root_catalogs[] = $cats['cat_id'];

			}

		}
		if ( count( $root_catalogs ) ) {
			foreach ( $root_catalogs as $cat_id ) {
				$sublevel = $level + 1;
				$pod_option = SelectFieldManagerCategories( '', $action, $cat_id, $sublevel );

				$selected = '';
				foreach ( $action AS $val ) {
					if ( $cat_id == $val ) {
						$selected = ' selected';

					}

				}
				$return .= '<option value="' . $cat_id . '"' . $selected . ' style="padding-left:' . $sublevel * 20 . 'px">' . $cat_info[$cat_id]['cat_name'] . '</option>';
				$return .= $pod_option;

			}

		}
		if ( $level ) {
			return $return;

		} else {
			return '<select class="selectstyle show-tick form-control input-sm" name="' . $name . '[]" id="' . $name . '" title="Выберите каталог(и)..." multiple data-actions-box="true" data-size="auto" data-live-search="true" data-selected-text-format="count" data-change="' . $name . '">' . $return . '</select>';

		}

	} else {
		return $return;
	
	}

}

// select тип дополнительного поля
function FieldManagerType( $action = 0, $title ) {
	global $field_manager_type;

	$return = '';

	if ( count( $field_manager_type ) ) {
		foreach(  $field_manager_type AS $key => $val ) {
			$selected = ( $key == $action ) ? ' selected' : '';
			$return .= '<option value="' . $key . '"' . $selected . '>' . $val['name'] . '</option>';

		}
		$title = ( (int)$action < 1 ) ? ' title="' . $title . '"' : '';
		return '<select class="selectstyle show-tick form-control input-sm" name="fm_type" id="fm_type"' . $title . ' data-size="auto" data-change="fm_type">' . $return . '</select>';

	} else {
		return $return;
	
	}

}
		if( ! is_array( $cache_areas ) ) {
			$cache_areas = array( $cache_areas );

		}

// склонение
function DecFun( $num, $dec_text = array() ) {
	if( ! is_array( $dec_text ) ) {
		return '';

	}

	$num = (int)$num;
	$num = $num % 100;
	if ( $num > 19 ) {
		$num = $num % 10;

	}

	switch ( $num ) {
		case 1:
			return $dec_text[0];
		case 2:
		case 3:
		case 4:
			return $dec_text[1];
		default:
			return $dec_text[2];
	}

}

// CSS стилизацыя checkbox
function CssStyleCheckbox( $name, $text = '', $checked = '', $location = 1 ) {

	if ( trim( $name ) == '' ) {
		return FALSE;

	}

	$text_l = '';
	$text_r = '';
	if ( trim( $text ) != '' ) {
		$text = '<span class="checked_text_info">' . $text . '</span>';
		if ( $location == 1 ) {
			$text_r = '<span class="checked_text_info">' . $text . '</span>';

		} else if ( $location == 2 ) {
			$text = explode( '|', $text );
			$text_l = '<span class="checked_text_info">' . $text[0] . '</span>';
			$text_r = '<span class="checked_text_info">' . $text[1] . '</span>';

		} else  {
			$text_l = '<span class="checked_text_info">' . $text . '</span>';

		}

	}
	$checked = ( (int)$checked == 1 ) ? ' checked=""' : '';

	$return = <<<HTML
<div class="checkbox-style-form">
	{$text_l}
	<div class="checkbox-style">
		<input id="{$name}" type="checkbox" name="{$name}"{$checked} value="1">
		<label for="{$name}">
			<span class="checked mdi mdi-check"></span>
			<span class="toggle"></span>
			<span class="unchecked mdi mdi-close"></span>
		</label>
	</div>
	{$text_r}
</div>
HTML;

	return $return;

}

function FieldsForm( $value_arr = FALSE ) {
	global $field_manager_type, $field_manager_info;
	
	$need_yes = 'обязательное поле';
	$need_no = 'не обязательное поле';

	if( ! is_array( $field_manager_type ) ) {
		return '';

	}

	if( ! is_array( $field_manager_info ) ) {
		return '';

	}

	$return = '';

	if ( $value_arr['ar_catalog'] ) {
		$ar_catalog = explode( ',', $value_arr['ar_catalog'] );

	}

	foreach ( $field_manager_info AS $val_arr ) {

		$style = ' style="display: none;"';
		$style_status = FALSE;

		$fm_cat_id = explode( ',', $val_arr['fm_cat_id'] );

		foreach( $fm_cat_id AS $cat_id ) {
			if ( in_array( $cat_id, $ar_catalog ) AND $style_status == FALSE ) {
				$style = ' style="display: block;"';
				$style_status = TRUE;

			}

		}

		$fm_alt_name = $field_manager_type[$val_arr['fm_type']]['prefix'] . '_' . $val_arr['fm_alt_name'];
		if( is_array( $value_arr ) ) {
			$value = $value_arr[$fm_alt_name];

		} else {
			$value = '';

		}

		if ( $val_arr['fm_statys_add'] == 1 ) {
			switch ( $val_arr['fm_type'] ) {
				case 1 :
						$fm_description = ( $val_arr['fm_description'] ) ? '<span class="label label-primary pull-left" data-toggle="tooltip" data-html="true" data-placement="top" title="' . $val_arr['fm_description'] . '">?</span>' : '';

						$fm_need = ( $val_arr['fm_need'] ) ? $need_yes : $need_no;

						$return .= <<<HTML
				<div class="form-group"{$style} data-fild_form="{$val_arr['fm_id']}">
					<label for="{$fm_alt_name}" class="col-lg-3 col-md-3 col-sm-3 control-label">{$val_arr['fm_neme']}:</label>
					<div class="col-lg-6 col-md-6 col-sm-6">
						<input type="text" class="form-control input-sm" id="{$fm_alt_name}" name="{$fm_alt_name}" value="{$value}" placeholder="{$fm_need}">
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3">{$fm_description}</div>
				</div>
HTML;

					break;

				case 2 :
						$wysiwyg = ( (int)$val_arr['fm_html_wysiwyg'] == 1 ) ? ' data-textarea="tinymce"' : '';

						$fm_description = ( $val_arr['fm_description'] ) ? '<em><small>' . $val_arr['fm_description'] . '</small></em>' : '';

						$fm_need = ( $val_arr['fm_need'] ) ? '<p class="text-warning"><small>' . $need_yes . '</small></p>' : '<p class="text-warning"><small>' . $need_no . '</small></p>';

						$return .= <<<HTML
				<div class="form-group"{$style} data-fild_form="{$val_arr['fm_id']}">
					<label for="{$fm_alt_name}" class="col-lg-3 col-md-3 col-sm-3 control-label">{$val_arr['fm_neme']}:{$fm_need}{$fm_description}</label>
					<div class="col-lg-9 col-md-9 col-sm-9">
						<textarea class="form-control input-sm" rows="5" id="{$fm_alt_name}" name="{$fm_alt_name}"{$wysiwyg}>{$value}</textarea>
					</div>
				</div>
HTML;

					break;

				case 3 :
						$fm_field_contents = explode( '||', $val_arr['fm_field_contents'] );

						$select = '';
						if ( $fm_field_contents > 1 ) {
							$data_title = ( $val_arr['fm_need'] == 1 ) ? 'title="' . $need_yes . '"' : 'title="' . $need_no . '"';

							if ( $val_arr['fm_multi'] == 1 ) {
								$select = '<select class="selectstyle show-tick form-control input-sm" id="' . $fm_alt_name . '" name="' . $fm_alt_name . '[]"' . $data_title . ' multiple data-actions-box="true" data-size="auto" data-live-search="true" data-selected-text-format="count" data-change="' . $fm_alt_name . '">';

							} else {
								$select = '<select class="selectstyle show-tick form-control input-sm" id="' . $fm_alt_name . '" name="' . $fm_alt_name . '"' . $data_title . ' data-size="auto" data-change="' . $fm_alt_name . '">';

							}
							$select .= ( $val_arr['fm_need'] == 1 ) ? '' : '<option value="0">Нет таднных</option>';

							$value = explode( '||',  $value );
							foreach ( $fm_field_contents AS $val ) {
								$selected = ( in_array( $val, $value ) ) ? ' selected' : '';
								$select .= '<option value="' . $val . '"' . $selected . '>' . $val . '</option>';

							}
							$select .= '</select>';

						}
						$fm_description = ( $val_arr['fm_description'] ) ? '<span class="label label-primary pull-left" data-toggle="tooltip" data-html="true" data-placement="top" title="' . $val_arr['fm_description'] . '">?</span>' : '';

						$return .= <<<HTML
				<div class="form-group"{$style} data-fild_form="{$val_arr['fm_id']}">
					<label for="{$fm_alt_name}" class="col-lg-3 col-md-3 col-sm-3 control-label">{$val_arr['fm_neme']}:</label>
					<div class="col-lg-6 col-md-6 col-sm-6">
						{$select}
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3">{$fm_description}</div>
				</div>
HTML;

					break;

				case 4 :
						$value = $value ? 1 : 0;
						$checkbox = CssStyleCheckbox( $fm_alt_name, $val_arr['fm_field_contents'], $value, 2 );

						$fm_description = ( $val_arr['fm_description'] ) ? '<span class="label label-primary pull-left" data-toggle="tooltip" data-html="true" data-placement="top" title="' . $val_arr['fm_description'] . '">?</span>' : '';

						$return .= <<<HTML
				<div class="form-group"{$style} data-fild_form="{$val_arr['fm_id']}">
					<label for="{$fm_alt_name}" class="col-lg-3 col-md-3 col-sm-3 control-label">{$val_arr['fm_neme']}:</label>
					<div class="col-lg-6 col-md-6 col-sm-6">
						{$checkbox}
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3">{$fm_description}</div>
				</div>
HTML;

					break;

				case 5 :
						$ar_id = ( $value_arr['ar_id'] > 0 ) ? $value_arr['ar_id'] : 0;

						$value_count = ( $value ) ? count( explode( ',', $value ) ) : 0;

						if ( $val_arr['fm_file_type'] == 1 ) {
							$dec = array( 'изображение прикреплено', 'изображения прикреплено', 'изображений прикреплено' );

						} else {
							$dec = array( 'файл прикреплен', 'файла прикреплено', 'файлов прикреплено' );

						}
						$value_count .= ' ' . DecFun( $value_count, $dec );

						if ( $val_arr['fm_multi'] == 1 ) {
							$count_limit = 'мульти загрузочное поле';

						} else {
							if ( $val_arr['fm_file_type'] == 1 ) {
								$count_limit = 'лимит 1 изображение';
							} else {
								$count_limit = 'лимит 1 файл';

							}
						}

						$return .= <<<HTML
				<div class="form-group"{$style} data-fild_form="{$val_arr['fm_id']}">
					<label for="{$fm_alt_name}" class="col-lg-3 col-md-3 col-sm-3 control-label">{$val_arr['fm_neme']}:<p class="text-warning"><small>{$count_limit}</small></p></label>
					<div class="col-lg-6 col-md-6 col-sm-6">
						<div class="btn btn-info btn-sm btn-block" data-btn="filemanager" data-fm_id="{$val_arr['fm_id']}" data-ar_id="{$ar_id}">открыть</div>
						<input type="hidden" class="form-control input-sm hidden" id="{$fm_alt_name}" name="{$fm_alt_name}" data-input="fm_id_{$val_arr['fm_id']}" value="{$value}">
						<p class="text-info text-right"><small data-content="fm_id_{$val_arr['fm_id']}">{$value_count}</small></p>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3"><span class="label label-primary pull-left" data-toggle="tooltip" data-html="true" data-placement="top" title="Менеджер загрузки изображений и файлов на сервер.">?</span></div>
				</div>
HTML;

					break;

			}

		}

	}
	return $return;

}


// css3 animation loading
function CssLoading() {
	$return = <<<HTML
<div class="css_loading_form" data-alt="LOADING" data-content="css_loading" style="display: none;">
	<div class="cssload-thecube">
		<div class="cssload-cube cssload-c1"></div>
		<div class="cssload-cube cssload-c2"></div>
		<div class="cssload-cube cssload-c4"></div>
		<div class="cssload-cube cssload-c3"></div>
	</div>
</div>
HTML;

	return $return;

}

?>
