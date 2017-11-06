<?php

// 百度爱玩
define( 'DS' , DIRECTORY_SEPARATOR );
define( 'AROOT' , dirname( __FILE__ ) . DS  );

include('common.php');
include('BaiduHezuoModel.php');

use Joyme\core\Request;

$k = Request::get('k', '');
$t = Request::get('t', '');
$a = Request::get('a', '');
if(!$k || !$t){
	echo json_encode(array('rs'=>1, 'msg'=>'参数错误'));exit;
}

$expiration = 3600; // 过期时间

$s = AROOT . 'cache/'.$k.'_'.$t . '.json'; // 缓存文件
$c = AROOT . $t . '.php'; // 控制器文件

if($a == 'clear'){
	@unlink($s);
}

if( file_exists( $s ) && (filemtime( $s )<time()-$expiration) ){
	echo file_get_contents($s);
}else if( file_exists( $c ) ){
	ob_start();
	include($c);
	$data = ob_get_contents();
	ob_end_flush();
	file_put_contents($s, $data);
}else{
	echo json_encode(array('rs'=>2, 'msg'=>'模块不存在'));exit;
}