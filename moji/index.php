<?php

/**
** 墨迹合作
** crontab 定时执行脚本,每2小时执行一次
** 参数1 环境后缀【alpha,beta,com】
** 参数2 分类id 【1339,1086,236,67144,67143,67137】
** 参数3 第一次执行脚本使用【固定值1】 仅代表第一次执行
**/
define( 'DS' , DIRECTORY_SEPARATOR );
define( 'AROOT' , dirname( __FILE__ ) . DS  );
$startTime = microtime(true);
include('common.php');
include('ArticleModel.php');
include('fn_common.php');

use Joyme\core\Request;

if(!empty($argv)){
	$cat = $argv[2];
	$isnew = !empty($argv[3]) ? $argv[3] : 0;
}else{
	$cat = intval(Request::get('cat', ''));
	$isnew = Request::get('isnew') ? 1 : 0;
}

$expiration = 7200; // 过期时间

if($isnew){
	$cache = AROOT . 'cache/first_'.$cat. '.json'; // 缓存文件
}else{
	$cache = AROOT . 'cache/'.$cat. '.json'; // 缓存文件
}

$now = time();
$columninfo = array();
$istag = false;
if( file_exists( $cache ) && $isnew ){
	echo file_get_contents($cache);exit;
}
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






