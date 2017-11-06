<?php
//连接数据库
$com = substr($_SERVER['HTTP_HOST'],strrpos($_SERVER['HTTP_HOST'],'.')+1);
$cat = isset($cat) ? $cat : '';

if($com === 'beta'){
	$host = 'alyweb002.prod';
	$user = 'wikiuser';
	$pwd = '123456';
}else if($com === 'com'){
    if($cat=='gift'){
        $host = 'alyweb005.prod';
        $user = 'wikiuser';
        $pwd = '123456';
    }else{
        $host = 'rm-2zed40rbv0xc9iam0.mysql.rds.aliyuncs.com';
        $user = 'td_userrw';
        $pwd = '2QWdf#Z9fc0o*$zE';
    }
}
else{
	$host = '172.16.75.65';
	$user = 'rd';
	$pwd = 'rd';
}
$link = mysql_connect($host, $user, $pwd );
if(!$link){
	error(0);
}
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