<?php
//json 中文转换
define('CACHEPATH', 'sougoucache');//文件路径常量
error_reporting(0);
function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            $array[$key] = $function($value);
        }
 
        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
    $recursive_counter--;
}
function JSON($array) {
 arrayRecursive($array, 'urlencode', true);
 $json = json_encode($array);
 return urldecode($json);
}

//错误函数
function error($n){
	$errorArr = array(
		0 => '数据库连接失败！',
		1 => '数据库不存在',
		2 => '链接错误',
		3 => '缓存失败',
		4 => '删除缓存失败',
		5 => '数据为空'
	);
	die(JSON(array($errorArr[$n])));
}

//缓存路径
function cacheFilePath($cat){
	return CACHEPATH.'/'.$cat.'/';
}

//清楚缓存
function delcache($path){
	$path_source = opendir($path);
	while($file = readdir($path_source)){
		if($file != '.' && $file != '..'){
			$res = unlink($path.$file);
			if($res===false){
				error(4);
			}
		}
	}
	
}

//mysql 函数
function num($sql){
	$res = mysql_query($sql);
	$num = mysql_fetch_row($res);
	return $num[0];
}

function msg($sql){
	$res = mysql_query($sql);
	$msgArr = array();
	while($row = mysql_fetch_assoc($res)){
		$msgArr[] = $row;
	}
	return $msgArr;
}