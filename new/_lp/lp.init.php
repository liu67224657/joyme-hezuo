<?php

if( !defined('AROOT') ) die('NO AROOT!');
if( !defined('DS') ) define( 'DS' , DIRECTORY_SEPARATOR );

// define constant
define( 'IN' , true );

define( 'ROOT' , dirname( __FILE__ ) . DS );
define( 'CROOT' , ROOT . 'core' . DS  );

// define 
error_reporting(E_ALL^E_NOTICE);
ini_set( 'display_errors' , false );

include_once( CROOT . 'lib' . DS . 'core.function.php' );
@include_once( AROOT . 'lib' . DS . 'app.function.php' );

include_once( CROOT . 'config' .  DS . 'core.config.php' );
include_once( AROOT . 'config' . DS . 'app.config.php' );

include_once($GLOBALS['libPath']);
require_once( AROOT .  'config/db.config.php' );


define( 'MODEL_PATH' , AROOT . 'model' . DS  );
function autoload($class){
	$file = MODEL_PATH.$class . '.class.php';
	if (is_file($file)) {
		require_once($file);
	}else{
		return;
	}
}
spl_autoload_register("autoload");


$c = $GLOBALS['c'] = v('c') ? v('c') : c('default_controller');
$a = $GLOBALS['a'] = v('a') ? v('a') : c('default_action');

$c = basename(strtolower( z($c) ));
$a =  basename(strtolower( z($a) ));

$post_fix = '.class.php';

$cont_file = AROOT . 'controller'  . DS . $c . $post_fix;

$class_name = $c;

if( !file_exists( $cont_file ) )
{
	$cont_file = CROOT .DS . $c . $post_fix;

	if( !file_exists( $cont_file ) ) die('Can\'t find file - ' . $c . $post_fix );
}
require_once( $cont_file );
if( !class_exists( $class_name ) ) die('Can\'t find class - '   .  $class_name );


$o = new $class_name;
if( !method_exists( $o , $a ) ) die('Can\'t find method - '   . $a . ' ');


if(strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE && @ini_get("zlib.output_compression")) ob_start("ob_gzhandler");
call_user_func( array( $o , $a ) );


