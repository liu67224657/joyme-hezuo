<?php
/**
 *三、新增或更新的资讯总量
 */
header("Content-type: text/html; charset=utf-8");
include 'helper.fn.php';
//定义资讯分类
$catArr = array('all'=>0, 'news'=>1, 'strategy'=>2, 'video'=>3, 'gamedata'=>4, 'activity'=>5, 'gift'=>6);
$cat = isset($_GET['category']) ? addslashes($_GET['category']) : '';
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$end = isset($_GET['end']) ? intval($_GET['end']) : 0;
$type = isset($_GET['type']) ? addslashes($_GET['type']) : '';

if(!in_array($cat, array_keys($catArr)) || ($end <= $start)){
	error(2);
}
//判断缓存
$cache_file_path = cacheFilePath($cat).''.$type.'_num_'.$cat.'_'.$start.'_'.$end.'.txt';
if(file_exists($cache_file_path)){
	echo file_get_contents($cache_file_path);exit;
}
//连接数据库
include 'db.php';
$whereStr = '';
if($cat=='gift'){
	//礼包
	if($type == 'update'){
		$whereStr = ' and start_time >= "'.date('Y-m-d H:i:s', $start).'" and start_time <= "'.date('Y-m-d H:i:s', $end).'"';
	}else if($type == 'new'){
		$whereStr = ' and createtime >= "'.date('Y-m-d H:i:s', $start).'" and createtime <= "'.date('Y-m-d H:i:s', $end).'"';
	}else{
		error(2);
	}
	$sql = "SELECT count(*) FROM activity WHERE remove_status = 'y'".$whereStr;
}else{
	//cms
	if($cat != 'all'){
		$whereStr .= ' and categoryid = '.$catArr[$cat];
	}
	if($type == 'update'){
		$whereStr .= ' and pubdate >= '.$start.' and pubdate <= '.$end;
	}else if($type == 'new'){
		$whereStr .= ' and senddate >= '.$start.' and senddate <= '.$end;
	}else{
		error(2);
	}
	$sql = 'SELECT COUNT(*) FROM dede_archives where ismake=1  and showpc=1'.$whereStr;
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