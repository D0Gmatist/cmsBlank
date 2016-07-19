<?php
/*
=====================================================
| CMS CATHARSIS SHOP - by D0Gmatist
-----------------------------------------------------
| http://d0gmatist.pro/
===== ===== ===== ===== ===== ===== ===== ===== =====
| Copyright (c) 2014
-----------------------------------------------------
| Данный код защищен авторскими правами
===== ===== ===== ===== ===== ===== ===== ===== =====
| Файл: costume.class.php
-----------------------------------------------------
| Назначение: Парсинг шаблонов
=====================================================
*/

if ( ! defined ( 'CATHARSIS_SHOP' ) ) { 
	die ( "Hacking attempt!" ); 
}

class shop_template {
	
	var $dir = '';
	var $template = null;
	var $copy_template = null;
	var $data = array ();
	var $block_data = array ();
	var $allow_php_include = true;
	var $include_mode = 'tpl';
	var $template_parse_time = 0;

    function __construct(){
		$this->dir = ROOT_DIR . '/templates/';    
	}
	
	function set( $name, $var ) {
		if( is_array( $var ) && count( $var ) ) {
			foreach ( $var as $key => $key_var ) {
				$this->set( $key, $key_var );
			}
		} else {
			$this->data[$name] = str_ireplace( "{include", "&#123;include",  $var );
		}
	}
	
	function set_block( $name, $var ) {
		if( is_array( $var ) && count( $var ) ) {
			foreach ( $var as $key => $key_var ) {
				$this->set_block( $key, $key_var );
			}
		} else {
			$this->block_data[$name] = str_ireplace( "{include", "&#123;include",  $var );
		}
	}
	
	function load_template( $tpl_name ) {
		$time_before = $this->get_real_time();
		$url = @parse_url ( $tpl_name );
		$file_path = dirname ( $this->clear_url_dir($url['path'] ) );
		$tpl_name = pathinfo( $url['path'] );
		$tpl_name = totranslit( $tpl_name['basename'] );
		$type = explode( ".", $tpl_name );
		$type = strtolower( end( $type ) );
		if ($type != "tpl") {
			return "";
		}
		if ( $file_path AND $file_path != "." ) $tpl_name = $file_path . "/" . $tpl_name;
		if( stripos ( $tpl_name, ".php" ) !== false ) {
			die( "Не допустимое Имя шаблона: " . str_replace( ROOT_DIR, '', $this->dir ) . "/" . $tpl_name );
		}
		if( $tpl_name == '' || !file_exists( $this->dir . "/" . $tpl_name ) ) {
			die( "Костюм не найден: " . str_replace( ROOT_DIR, '', $this->dir ) . "/" . $tpl_name );
			return false;
		}
		$this->template = file_get_contents( $this->dir . "/" . $tpl_name );

	//*********************************************
		if ( strpos ( $this->template, "[aviable=" ) !== false ) {
			$this->template = preg_replace_callback ( "#\\[(aviable)=(.+?)\\](.*?)\\[/aviable\\]#is", array( &$this, 'check_module' ), $this->template );
		}
		if ( strpos ( $this->template, "[not-aviable=" ) !== false ) {
			$this->template = preg_replace_callback ( "#\\[(not-aviable)=(.+?)\\](.*?)\\[/not-aviable\\]#is", array( &$this, 'check_module' ), $this->template );
		}

		if ( strpos ( $this->template, "[cat_aviable=" ) !== false ) {
			$this->template = preg_replace_callback ( "#\\[(cat_aviable)=(.+?)\\](.*?)\\[/cat_aviable\\]#is", array( &$this, 'check_catalog' ), $this->template );
		}
		if ( strpos ( $this->template, "[not-cat_aviable=" ) !== false ) {
			$this->template = preg_replace_callback ( "#\\[(not-cat_aviable)=(.+?)\\](.*?)\\[/not-cat_aviable\\]#is", array( &$this, 'check_catalog' ), $this->template );
		}

		if ( strpos ( $this->template, "[group=" ) !== false ) {
			$this->template = preg_replace_callback ( "#\\[(group)=(.+?)\\](.*?)\\[/group\\]#is", array( &$this, 'check_group' ), $this->template );
		}
		if ( strpos ( $this->template, "[not-group=" ) !== false ) {
			$this->template = preg_replace_callback ( "#\\[(not-group)=(.+?)\\](.*?)\\[/not-group\\]#is", array( &$this, 'check_group' ), $this->template );
		}

		if ( strpos( $this->template, "{include file=" ) !== false ) {
			$this->include_mode = 'tpl';			
			$this->template = preg_replace_callback( "#\\{include file=['\"](.+?)['\"]\\}#i", array( &$this, 'load_file' ), $this->template );
		}
	//*********************************************

		$this->copy_template = $this->template;
		$this->template_parse_time += $this->get_real_time() - $time_before;
		return true;
	}

	function load_file( $matches=array() ) {
		global $db, $is_logged, $member_id, $config, $user_group, $_TIME, $lang_site, $langtranslit, $ruCountry;
		$name = $matches[1];
		$name = str_replace( chr(0), "", $name );
		$name = str_replace( '..', '', $name );
		$url = @parse_url ( $name );
		$type = explode( ".", $url['path'] );
		$type = strtolower( end( $type ) );
		if ( $type == "tpl" ) {
			return $this->sub_load_template( $name );
		}
		if ( $this->include_mode == "php" ) {
			if ( !$this->allow_php_include ) {
				return;
			}
			if ( $type != "php" ) {
				return "Для подключения разрешается только файлы с расширением: .tpl or .php";
			}
			if ( $url['path']{0} == "/" ) {
				$file_path = dirname ( ROOT_DIR . $url['path'] );
			} else {
				$file_path = dirname ( ROOT_DIR . "/" . $url['path'] );
			}
			$file_name = pathinfo($url['path'] );
			$file_name = $file_name['basename'];
			if ( stristr ( php_uname( "s" ) , "windows" ) === false ) {
				$chmod_value = @decoct( @fileperms( $file_path ) ) % 1000;
			}
	//*********************************************
			if ( stristr ( dirname ( $url['path'] ) , "uploads" ) !== false ) {
				return "Подключение файла из деректории /uploads/ запрещено";
			}
			if ( stristr ( dirname ( $url['path'] ) , "templates" ) !== false ) {
				return "Подключение файла из деректории /templates/ запрещено";
			}
			if ( stristr ( dirname ( $url['path'] ) , "system/configs" ) !== false ) {
				return "Подключение файла из деректории /system/configs/ запрещено";
			}
			if ( stristr ( dirname ( $url['path'] ) , "system/functions" ) !== false ) {
				return "Подключение файла из деректории /system/functions/ запрещено";
			}
			if ( stristr ( dirname ( $url['path'] ) , "system/classes" ) !== false ) {
				return "Подключение файла из деректории /system/classes/ запрещено";
			}
			if ( stristr ( dirname ( $url['path'] ) , "system/cache" ) !== false ) {
				return "Подключение файла из деректории /system/cache/ запрещено";
			}
	//*********************************************

			if ( $chmod_value == 777 ) {
				return "Файл {$url['path']} находится в папке, которая доступна для записи (CHMOD 777). В целях безопасности файлы на подключение от этих папок невозможно. Измените права на папку, что он не имел никаких прав на запись.";
			}
			if ( !file_exists( $file_path . "/" . $file_name ) ) {
				return "Файл {$url['path']} не найден.";
			}
			$url['query'] = str_ireplace( array( "_GET", "_FILES", "_POST", "_REQUEST", "_SERVER", "_COOKIE", "_SESSION" ) ,"Filtered", $url['query'] );
			if ( substr_count ( $this->template, "{include file=" ) < substr_count ( $this->copy_template, "{include file=" ) ) {
				return "Filtered";
			}
			if ( isset( $url['query'] ) AND $url['query'] ) {
				$module_params = array();
				parse_str( $url['query'], $module_params );
				extract( $module_params, EXTR_SKIP );
				unset( $module_params );
			}
			ob_start();
			$tpl = new shop_template();
			$tpl->dir = CATHARSIS_SHOP_DIR;
			include $file_path . "/" . $file_name;
			return ob_get_clean();
		}
		return '{include file="' . $name . '"}';
	}

	function sub_load_template( $tpl_name ) {
		$url = @parse_url ( $tpl_name );
		$file_path = dirname ( $this->clear_url_dir( $url['path'] ) );
		$tpl_name = pathinfo( $url['path'] );
		$tpl_name = totranslit( $tpl_name['basename'] );
		$type = explode( ".", $tpl_name );
		$type = strtolower( end( $type ) );
		if ( $type != "tpl" ) {
			return "Не допустимое Имя шаблона: " . $tpl_name;
		}
		if ( $file_path AND $file_path != "." ) {
			$tpl_name = $file_path."/".$tpl_name;
		}
		if ( strpos( $tpl_name, '/templates/' ) === 0) {
			$tpl_name = str_replace( '/templates/', '', $tpl_name );
			$templatefile = ROOT_DIR . '/templates/' . $tpl_name;
		} else $templatefile = $this->dir . "/" . $tpl_name;
		if( $tpl_name == '' || !file_exists( $templatefile ) ) {
			$templatefile = str_replace( ROOT_DIR, '', $templatefile );
			return "Шаблон не найден: " . $templatefile ;
			return false;
		}
		if( stripos ( $templatefile, ".php" ) !== false ) {
			return "Не допустимое Имя шаблона: " . $tpl_name;
		}
		$template = file_get_contents( $templatefile );

	//*********************************************
		if ( strpos ( $template, "[aviable=" ) !== false ) {
			$template = preg_replace_callback ( "#\\[(aviable)=(.+?)\\](.*?)\\[/aviable\\]#is", array( &$this, 'check_module' ), $template );
		}
		if ( strpos ( $template, "[not-aviable=" ) !== false ) {
			$template = preg_replace_callback ( "#\\[(not-aviable)=(.+?)\\](.*?)\\[/not-aviable\\]#is", array( &$this, 'check_module' ), $template );
		}

		if ( strpos ( $template, "[cat_aviable=" ) !== false ) {
			$template = preg_replace_callback ( "#\\[(cat_aviable)=(.+?)\\](.*?)\\[/cat_aviable\\]#is", array( &$this, 'check_catalog' ), $template );
		}
		if ( strpos ( $template, "[not-cat_aviable=" ) !== false ) {
			$template = preg_replace_callback ( "#\\[(not-cat_aviable)=(.+?)\\](.*?)\\[/not-cat_aviable\\]#is", array( &$this, 'check_catalog' ), $template );
		}

		if ( strpos ( $template, "[group=" ) !== false ) {
			$template = preg_replace_callback ( "#\\[(group)=(.+?)\\](.*?)\\[/group\\]#is", array( &$this, 'check_group' ), $template );
		}
		if ( strpos ( $template, "[not-group=" ) !== false ) {
			$template = preg_replace_callback ( "#\\[(not-group)=(.+?)\\](.*?)\\[/not-group\\]#is", array( &$this, 'check_group' ), $template );
		}
	//*********************************************

		return $template;

	}

	function check_module( $matches=array() ) {
		global $catharsis_module;
		$aviable = $matches[2];
		$block = $matches[3];
		if ( $matches[1] == "aviable" ) {
			$action = true; 
		} else {
			$action = false;
		}
		$aviable = explode( '|', $aviable );
		if( $action ) {
			if( ! ( in_array( $catharsis_module, $aviable ) ) and ( $aviable[0] != "global" ) ) {
				return "";
			}else {
				return $block;
			}
		} else {
			if( ( in_array( $catharsis_module, $aviable ) ) ) {
				return "";
			} else {
				return $block;
			}
		}
	}

	function check_catalog( $matches=array() ) {
		global $cat_id_tpl;
		$cat_aviable = $matches[2];
		$block = $matches[3];
		if ( $matches[1] == "cat_aviable" ) {
			$action = true; 
		} else {
			$action = false;
		}
		$cat_aviable = explode( '|', $cat_aviable );
		if( $action ) {
			if( ! ( in_array( $cat_id_tpl, $cat_aviable ) ) and ( $cat_aviable[0] != "global" ) ) {
				return "";
			}else {
				return $block;
			}
		} else {
			if( ( in_array( $cat_id_tpl, $cat_aviable ) ) ) {
				return "";
			} else {
				return $block;
			}
		}
	}

	function clear_url_dir( $var ) {
		if ( is_array( $var ) ) {
			return "";
		}
		$var = str_ireplace( ".php", "", $var );
		$var = str_ireplace( ".php", ".ppp", $var );
		$var = trim( strip_tags( $var ) );
		$var = str_replace( "\\", "/", $var );
		$var = preg_replace( "/[^a-z0-9\/\_\-]+/mi", "", $var );
		$var = preg_replace( '#[\/]+#i', '/', $var );
		return $var;
	}

	function check_group( $matches=array() ) {
		global $member_id;
		$groups = $matches[2];
		$block = $matches[3];
		if ($matches[1] == "group") {
			$action = true;
		} else {
			$action = false;
		}
		$groups = explode( ',', $groups );
		if( $action ) {
			if( ! in_array( $member_id['us_group'], $groups ) ) {
				return "";
			}
		} else {
			if( in_array( $member_id['us_group'], $groups ) ) {
				return "";
			}
		}
		return $block;
	}
	
	function _clear() {
		$this->data = array ();
		$this->block_data = array ();
		$this->copy_template = $this->template;
	}
	
	function clear() {
		$this->data = array ();
		$this->block_data = array ();
		$this->copy_template = null;
		$this->template = null;
	}
	
	function global_clear() {
		$this->data = array ();
		$this->block_data = array ();
		$this->result = array ();
		$this->copy_template = null;
		$this->template = null;
	}
	
	function compile( $tpl ) {
		$time_before = $this->get_real_time();
		if( count( $this->block_data ) ) {
			foreach ( $this->block_data as $key_find => $key_replace ) {
				$find_preg[] = $key_find;
				$replace_preg[] = $key_replace;
			}
			$this->copy_template = preg_replace( $find_preg, $replace_preg, $this->copy_template );
		}
		foreach ( $this->data as $key_find => $key_replace ) {
			$find[] = $key_find;
			$replace[] = $key_replace;
		}
		$this->copy_template = str_replace( $find, $replace, $this->copy_template );
		if( strpos( $this->template, "{include file=" ) !== false ) {
			$this->include_mode = 'php';			
			$this->copy_template = preg_replace_callback( "#\\{include file=['\"](.+?)['\"]\\}#i", array( &$this, 'load_file' ), $this->copy_template );
		
		}
		if( isset( $this->result[$tpl] ) ) $this->result[$tpl] .= $this->copy_template;
		else $this->result[$tpl] = $this->copy_template;
		$this->_clear();
		$this->template_parse_time += $this->get_real_time() - $time_before;
	}
	
	function get_real_time() {
		list ( $seconds, $microSeconds ) = explode( ' ', microtime() );
		return ( ( float ) $seconds + ( float ) $microSeconds );
	}
}

?>