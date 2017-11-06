<?php

// 百度爱玩
header("Content-type: text/json; charset=utf-8");

$strpos = explode(".",$_SERVER['SERVER_NAME']);
$env = array_pop($strpos);

if($env == 'com'){
	$GLOBALS['config']['db']['db_host'] = 'rm-2zed40rbv0xc9iam0.mysql.rds.aliyuncs.com';
	$GLOBALS['config']['db']['db_port'] = 3306;
	$GLOBALS['config']['db']['db_user'] = 'td_userrw';
	$GLOBALS['config']['db']['db_password'] = '2QWdf#Z9fc0o*$zE';
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
            '1'=>'资料',
            '2'=>'资讯',
            '3'=>'攻略',
            '4'=>'视频',
            '5'=>'礼包',
        );
$soncats = array(
            '1' => '最新资讯',
            '2' => '最新攻略',
            '3' => '英雄攻略',
            '4' => '攻略技巧',
            '5' => '出装攻略',
            '6' => '最新视频',
            '7' => '英雄视频',
            '8' => '解说视频',
            '9' => '',
        );
