<?php

header("Content-type: text/html; charset=utf-8");
define('JOYME', 'hao123');

/**
 * 数据库配置
 */
$_config = array();
$com = substr($_SERVER['HTTP_HOST'],strrpos($_SERVER['HTTP_HOST'],'.')+1);
$_config['jhost']['domain'] = '.joyme.'.$com;
$_config['jhost']['host']   = "http://www.joyme.".$com;
if($com === 'beta'){
	$host = '10.171.101.30';
	$user = 'wikiuser';
	$pwd = '123456';
}else if($com === 'com'){
	$host = 'rdsnu7brenu7bre.mysql.rds.aliyuncs.com';
	$user = 'wikiuser';
	$pwd = '123456';
}
// else if($com === 'alpha'){
	// $_config['db']['1']['dbhost'] = '127.0.0.1';
	// $_config['db']['1']['dbuser'] = 'root';
	// $_config['db']['1']['dbpw'] = '';
// }
else{
	$host	= '127.0.0.1';
	$user	= 'root';
	$pwd	= '123456';
}
$dbname	= 'cooperate';

require 'db.php';

//localhost/hao123/index.php?m=rank&p=add
$m = $_GET['m'];
$p = $_GET['p'];
if(file_exists($m.'.php')){
	require $m.'.php';
}else{
	exit('control file not exists');
}
