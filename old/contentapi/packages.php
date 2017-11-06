<?php
/**
 *搜狗app包接口
 */
header('Content-type: text/html; Charset=utf-8');
include 'helper.fn.php';
$cache_file_path = cacheFilePath('box').'packages'.'.txt';
if(file_exists($cache_file_path)){
	echo file_get_contents($cache_file_path);exit;
}
//连接数据库
include 'db.php';
$sql = 'SELECT package_name FROM joyme_spec WHERE is_package=1';
$resArr = msg($sql);
$str_json = JSON($resArr);
$res = file_put_contents($cache_file_path, $str_json);
if($res){
	echo $str_json;
}else{
	error(3);
}