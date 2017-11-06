<?php if ( !defined('JOYME') ) exit('No direct script access allowed');
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
//连接数据库服务器
$link = mysql_connect($host, $user, $pwd);
if( !$link ){
	exit('Database connection has failed');
}
//选择数据库
$dbselect = mysql_select_db($dbname);
if( !$dbselect ){
	exit('Database no exists');
}
mysql_query('set names utf8');
/**
* insert
*@param tablename
*@param data
*/
function insertData($table, $data){
	$fields = implode(', ', array_keys($data));
	$values = implode("', '", array_values($data));
	$sql = 'INSERT INTO '.$table.' ( '.$fields.' ) VALUES ( \''.$values.'\' )';
	return mysql_query($sql);
}

/**
* select
*@param tablename
*@param where
*/
function selectData($table, $where=''){
	$sql = 'SELECT * FROM '.$table.' '.$where;
	$res = mysql_query($sql);
	$data = array();
	while($row = mysql_fetch_assoc($res)){
		$data[] = $row;
	}
	return $data;
}

/**
* update
*@param tablename
*@param data
*@param where
*/
function updateData($table, $data, $where){
	$key_val = array();
	foreach($data as $k=>$v){
		$key_val[] = $k." = '".$v."'";
	}
	$sql = 'UPDATE '.$table.' SET '.implode(',', $key_val).' '.$where;
	return mysql_query($sql);
}

/**
* delete
*@param tablename
*@param where
*/
function delData($table, $where){
	$sql = 'DELETE FROM '.$table.$where;
	return mysql_query($sql);
}