<?php
/**
 *五、下线的资讯
 */
header("Content-type: text/html; charset=utf-8");
include 'helper.fn.php';
//定义资讯分类
$catArr = array('all'=>0, 'news'=>1, 'strategy'=>2, 'gift'=>6);
$cat = isset($_GET['catee']) ? addslashes($_GET['catee']) : '';
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
if(!in_array($cat, array_keys($catArr))){
	error(2);
}
//判断缓存
$cache_file_path = cacheFilePath($cat).'getbroken_'.$cat.'_'.$start.'.txt';
if(file_exists($cache_file_path)){
	echo file_get_contents($cache_file_path);exit;
}
//连接数据库
include 'db.php';
$resArr = array();
if($cat=='gift'){
	//礼包接口数据库
	$sql_num = "SELECT count(*) FROM activity WHERE remove_status = 'n' AND createtime >= '".date('Y-m-d H:i:s', $start)."'";
	$total = num($sql_num);
	$sql = "SELECT activity_id, activity_subject FROM activity WHERE remove_status = 'n' AND createtime >= '".date('Y-m-d H:i:s', $start)."'";
	$msg = msg($sql);
	foreach($msg as $k=>$v){
		$resArr[$k]['uniqid'] = $v['activity_id'];
		$resArr[$k]['title'] = $v['activity_subject'];
		$resArr[$k]['url'] = 'http://www.joyme.'.$com.'/gift/'.$v['activity_id'];
	}
}else{
	//资讯接口数据库
	echo JSON(array('total'=>0, 'ids'=>$resArr));exit;
}
$str_json = JSON(array('total'=>$total, 'ids'=>$resArr));
$res = file_put_contents($cache_file_path, $str_json);
if($res){
	echo $str_json;
}else{
	error(3);
}


