<?php
/**
 *搜狗盒子文章接口
 */
header('Content-type: text/html; Charset=utf-8');
include 'helper.fn.php';
//判断缓存
$name = isset($_GET['name']) ? addslashes($_GET['name']) : '';
if($name==''){
	error(2);
}
$cache_file_path = cacheFilePath('box').$name.'.txt';
if(file_exists($cache_file_path)){
	echo file_get_contents($cache_file_path);exit;
}

//连接数据库
include 'db.php';
$sql_game = 'select spec_name, package_name, spec_pic_url, file_path from joyme_spec WHERE package_name="'.$name.'"';
$game = msg($sql_game);
if(empty($game)){
	error(5);
}
$sql_news = 'select image_url, title, archive_publish_time, description, categoryid, html_path, html_file from joyme_point_archive WHERE spec_file_path="'.$game[0]['file_path'].'" limit 1000';
$resArr = msg($sql_news);

//定义分类
$cat = array(1=>'新闻', 2=>'攻略');

$news = array();
foreach($resArr as $k=>$v){
	$news[$k]['createtime'] = $v['archive_publish_time'];
	$news[$k]['type'] = ($v['categoryid']=0 && $v['categoryid']<=2) ? $cat[$v['categoryid']] : '其他';
	$news[$k]['title'] = $v['title'];
	$news[$k]['game'] = $game[0]['spec_name'];
	$news[$k]['package'] = $game[0]['package_name'];
	$news[$k]['icon'] = $game[0]['spec_pic_url'];
	$news[$k]['updatetime'] = $v['archive_publish_time'];
	$news[$k]['image'] = $v['image_url'];
	$news[$k]['source'] = '着迷网';
	$news[$k]['description'] = $v['description'];
	$news[$k]['url'] = 'http://marticle.joyme.'.$com.'/marticle/'.$v['html_path'].'/'.$v['html_file'];
}
$str_json = JSON($news);
$res = file_put_contents($cache_file_path, $str_json);
if($res){
	echo $str_json;
}else{
	error(3);
}