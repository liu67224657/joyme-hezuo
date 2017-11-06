<?php
/**
 *二、资讯总资源列表
 */
header("Content-type: text/html; charset=utf-8");
include 'helper.fn.php';
include 'cmsurl.fn.php';
//定义资讯分类
$catArr = array('all'=>0, 'news'=>1, 'strategy'=>2, 'gift'=>6);
$cat = isset($_GET['category']) ? addslashes($_GET['category']) : '';
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$page = isset($_GET['page'])&&($_GET['page']>=0) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 100;
$limit = $limit>1000 ? 1000 : $limit;
if(!in_array($cat, array_keys($catArr))){
	error(2);
}
//判断缓存
$cache_file_path = cacheFilePath($cat).'all_'.$cat.'_'.$start.'_'.$page.'_'.$limit.'.txt';
if(file_exists($cache_file_path)){
	//echo file_get_contents($cache_file_path);exit;
}
//连接数据库
include 'db.php';

$whereStr = '';
if($cat=='gift'){
	//礼包
	if($start != 0){
		$whereStr .= " AND createtime >= '".date('Y-m-d H:i:s', $start)."'";
	}
	$whereStr .= ' limit '.($page-1)*$limit .' , '.$limit;
	$field = ' activity_id, activity_subject, activity_desc, activity_picurl, start_time';
	$sql = "SELECT $field FROM activity WHERE remove_status = 'y'".$whereStr;
	$res = mysql_query($sql);
	$i=0;
	$resArr = array();
	while($row = mysql_fetch_assoc($res)){
	  $resArr[$i]['uniqid'] = $row['activity_id'];
	  $resArr[$i]['title'] =addslashes(preg_replace("/[\s]+|&nbsp;|\'|\"/","", strip_tags($row['activity_subject'])));
	  $resArr[$i]['url'] = 'http://www.joyme.'.$com.'/gift/'.$row['activity_id'];
	  $resArr[$i]['img'] = $row['activity_picurl'];
	  $resArr[$i]['summary'] = addslashes(preg_replace("/[\s]+|&nbsp;|\'|\"/","", strip_tags($row['activity_desc'])));
	  $resArr[$i]['datetime'] = $row['start_time'];
	  $resArr[$i]['category'] = $cat;
	  $i++;
	}
}else{
	//cms
	if($cat != 'all'){
		$whereStr .= ' and categoryid = '.$catArr[$cat];
	}
	if($start != 0){
		$whereStr .= ' and pubdate >= '.$start;
	}
	$whereStr .= ' limit '.($page-1)*$limit .' , '.$limit;

	$sql = 'SELECT *, a.id, a.description FROM dede_archives a, dede_arctype b where a.typeid=b.id and ismake=1 and showpc=1 '.$whereStr;
	$res = mysql_query($sql);
	$i=0;
	$resArr = array();
	while($row = mysql_fetch_assoc($res)){
		$url = GetFileUrl($row['id'],$row['typeid'],$row['senddate'],$row['title'],$row['ismake'],
		$row['arcrank'],$row['namerule'],$row['typedir'],$row['money'],$row['filename'],$row['moresite'],$row['siteurl'],$row['sitepath']);

		$resArr[$i]['uniqid'] = $row['id'];
		$resArr[$i]['title'] = addslashes(preg_replace("/[\s]+|&nbsp;|\'|\"/","", strip_tags($row['title'])));
		$resArr[$i]['url'] = 'http://'.$url;
		$resArr[$i]['img'] = 'http://www.joyme.com'.$row['litpic'];
		$resArr[$i]['summary'] = addslashes(preg_replace("/[\s]+|&nbsp;|\'|\"/","", strip_tags($row['description'])));
		$resArr[$i]['datetime'] = date('Y-m-d H:i:s', $row['senddate']);
		$resArr[$i]['category'] = $cat;
		$i++;
	}
}

$str_json = JSON($resArr);
$res = file_put_contents($cache_file_path, $str_json);
if($res){
	echo $str_json;
}else{
	error(3);
}
