<?php
/**
 *一、资讯总数接口
 */
header("Content-type: text/html; charset=utf-8");
include 'helper.fn.php';
//定义资讯分类
$catArr = array('all'=>0, 'news'=>1, 'strategy'=>2, 'gift'=>6);
$cat = isset($_GET['category']) ? addslashes($_GET['category']) : '';
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
if(!in_array($cat, array_keys($catArr))){
	error(2);
}
//判断缓存
$cache_file_path = cacheFilePath($cat).'/all_num_'.$cat.'_'.$start.'.txt';
if(file_exists($cache_file_path)){
	echo file_get_contents($cache_file_path);exit;
}
//连接数据库
include 'db.php';

//SQL
$whereStr = '';
if($cat=='gift'){
	if($start != 0){
		$whereStr = " AND createtime >= '".date('Y-m-d H:i:s', $start)."'";
	}
	$sql = "SELECT count(*) FROM activity WHERE remove_status = 'y'".$whereStr;
}else{
	if($cat != 'all'){
		$whereStr .= ' and categoryid = '.$catArr[$cat];
	}

	if($start != 0){
		$whereStr .= ' and pubdate >= '.$start;
	}

	$sql = 'SELECT COUNT(*) FROM dede_archives where ismake=1 and showpc=1 '.$whereStr;
}

$res = mysql_query($sql);
$row = mysql_fetch_row($res);

$str_json = JSON(array("category"=>$cat, "total"=>$row[0]));
$res = file_put_contents($cache_file_path, $str_json);
if($res){
	echo $str_json;
}else{
	error(3);
}