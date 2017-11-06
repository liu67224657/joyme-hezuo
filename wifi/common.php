<?php

// 墨迹天气
header("Content-type: text/json; charset=utf-8");

if(!empty($argv)){
	$env = $argv[1];
}else{
	$strpos = explode(".", $_SERVER['SERVER_NAME']);
	$env = array_pop($strpos);
}

if($env == 'com'){
    $GLOBALS['config']['db']['db_host'] = 'rm-2zed40rbv0xc9iam0.mysql.rds.aliyuncs.com';
    $GLOBALS['config']['db']['db_port'] = 3306;
    $GLOBALS['config']['db']['db_user'] = 'td_userrw';
    $GLOBALS['config']['db']['db_password'] = '2QWdf#Z9fc0o*$zE';
	$GLOBALS['config']['db']['db_name'] = 'article_cms';
	$pathkey ='prod';
}else if($env == 'beta'){
	$GLOBALS['config']['db']['db_host'] = 'alyweb002.prod';
	$GLOBALS['config']['db']['db_port'] = 3306;
	$GLOBALS['config']['db']['db_user'] = 'wikipage';
	$GLOBALS['config']['db']['db_password'] = 'wikipage';
	$GLOBALS['config']['db']['db_name'] = 'article_cms';
	$pathkey ='beta';
}else{
	$GLOBALS['config']['db']['db_host'] = '172.16.75.75';
	$GLOBALS['config']['db']['db_port'] = 3306;
	$GLOBALS['config']['db']['db_user'] = 'root';
	$GLOBALS['config']['db']['db_password'] = '654321';
	$GLOBALS['config']['db']['db_name'] = 'article_cms';
	$pathkey ='alpha';
}

if ( php_uname( 's' ) === 'Linux' ) {
	$libPath = '/opt/www/joymephplib/'.$pathkey.'/phplib.php';
}else{
	$libPath =  'D:/wamp/www/workspace/joymephplib/trunk/phplib.php';
}


if(file_exists($libPath)){
	require($libPath);
}else{
	die('公共库加载失败');
}
// 新游资讯
$cats = array(1339);
// 趣图
$tagids = array(1334);

$channle = array(1339=>'新游资讯',1334=>'趣图');

$domain = 'http://www.joyme.'.$env;

$joymelogo = 'http://joymepic.joyme.com/article/uploads/161116/80-161116140630527.jpg';
