<?php

/**
** wifi万能钥匙合作
**/
define( 'DS' , DIRECTORY_SEPARATOR );
define( 'AROOT' , dirname( __FILE__ ) . DS  );
$startTime = microtime(true);
include('common.php');
include('ArticleModel.php');
include('fn_common.php');

use Joyme\core\Request;

$cat = intval(Request::get('channelId', 0));
$page = intval(Request::get('page', 1));
$page = $page<=1?1:$page;
//$limit = intval(Request::get('num', 100));
$limit = 100;
$isnew = 0;

$expiration = 300; // 过期时间
$now = time();

//全局变量
$columninfo = array();
$istag = false;

$cache = AROOT . 'cache/'.$cat. '_'.$page.'.json'; // 缓存文件

if( file_exists( $cache ) && (filemtime( $cache )>$now-$expiration) ){
	echo file_get_contents($cache);exit;
}
$dbr = ArticleModel::getDBr();
if(in_array($cat, $cats)){
	include('fn_cat.php');
	index();
}else if(in_array($cat, $tagids)){
	$istag = true;
	include('fn_tag.php');
	index();
}else{
	$msg = '参数错误';
	err($msg);
	exit;
}






