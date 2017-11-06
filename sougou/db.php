<?php
//连接数据库
$com = substr($_SERVER['HTTP_HOST'],strrpos($_SERVER['HTTP_HOST'],'.')+1);
if($com === 'beta'){
	$host = 'alyweb002.prod';
	$user = 'wikiuser';
	$pwd = '123456';
}else if($com === 'com'){
	$host = 'alyweb005.prod';
	$user = 'wikiuser';
	$pwd = '123456';
}
// else if($com === 'alpha'){
	// $_config['db']['1']['dbhost'] = '127.0.0.1';
	// $_config['db']['1']['dbuser'] = 'root';
	// $_config['db']['1']['dbpw'] = '';
// }
else{
	$host = '172.16.75.65';
	$user = 'rd';
	$pwd = 'rd';
}
$link = mysql_connect($host, $user, $pwd );
if(!$link){
	error(0);
}
$cat = isset($cat) ? $cat : '';
if($cat=='gift'){
	//礼包
	$db = mysql_select_db('CONTENT');
}else{
	//cms
	$db = mysql_select_db('article_cms');
}
if(!$db){
	error(1);
}
mysql_query("set names charset utf8");