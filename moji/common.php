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
$GLOBALS['libPath'] = '/opt/www/joymephplib/'.$pathkey.'/phplib.php';
// $libPath =  'D:/wamp/www/joymephplib/trunk/phplib.php';

if(file_exists($libPath)){
	require($libPath);
}else{
	die('公共库加载失败');
}
// 新游资讯 1339,原创专栏 1086,游戏测评 236
$cats = array(1339,1086,236);
// 搞笑动图、美女图、精美COS
$tagids = array(67144,67143,67137);

$domain = 'https://www.joyme.'.$env;

$joymelogo = 'http://joymepic.joyme.com/article/uploads/161116/80-161116140630527.jpg';
