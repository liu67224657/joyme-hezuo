<?php

// 虎牙直播合作
header("Content-type: text/json; charset=utf-8");

$strpos = explode(".",$_SERVER['SERVER_NAME']);
$env = array_pop($strpos);

if($env == 'com'){
	$pathkey ='prod';
}else if($env == 'beta'){
	$pathkey ='beta';
}else{
	$pathkey ='alpha';
}
$GLOBALS['libPath'] = '/opt/www/joymephplib/'.$pathkey.'/phplib.php';
// $libPath =  'D:/wamp/www/joymephplib/trunk/phplib.php';

if(file_exists($libPath)){
	require($libPath);
}else{
	die('公共库加载失败');
}

$api = 'http://www.huya.com/cache.php';