<?php

// 百度爱玩

$strpos = explode(".",$_SERVER['SERVER_NAME']);
$env = array_pop($strpos);

if($env == 'com'){
	$GLOBALS['config']['db']['db_host'] = 'alyweb005.prod';
	$GLOBALS['config']['db']['db_port'] = 3306;
	$GLOBALS['config']['db']['db_user'] = 'wikipage';
	$GLOBALS['config']['db']['db_password'] = 'wikipage2015';
	$GLOBALS['config']['db']['db_name'] = 'wikiurl';
	$pathkey ='prod';
}else if($env == 'beta'){
	$GLOBALS['config']['db']['db_host'] = 'alyweb002.prod';
	$GLOBALS['config']['db']['db_port'] = 3306;
	$GLOBALS['config']['db']['db_user'] = 'wikipage';
	$GLOBALS['config']['db']['db_password'] = 'wikipage';
	$GLOBALS['config']['db']['db_name'] = 'wikiurl';
	$pathkey ='beta';
}else{
	$GLOBALS['config']['db']['db_host'] = '172.16.75.75';
	$GLOBALS['config']['db']['db_port'] = 3306;
	$GLOBALS['config']['db']['db_user'] = 'root';
	$GLOBALS['config']['db']['db_password'] = '654321';
	$GLOBALS['config']['db']['db_name'] = 'wikiurl';
	$pathkey ='alpha';
}
$GLOBALS['libPath'] = '/opt/www/joymephplib/'.$pathkey.'/phplib.php';
// $libPath =  'D:/wamp/www/joymephplib/trunk/phplib.php';

if(file_exists($libPath)){
	require($libPath);
}else{
	die('公共库加载失败');
}

$maincats = array(
            '1'=>'资讯',
            '2'=>'攻略',
            '3'=>'视频',
            '4'=>'礼包'
        );
