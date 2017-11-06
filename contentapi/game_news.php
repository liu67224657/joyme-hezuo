<?php
/**
 *超级攻略文章接口
 */
header('Content-type: text/html; Charset=utf-8');
include 'helper.fn.php';
//判断缓存
$name = isset($_GET['name']) ? addslashes($_GET['name']) : '';
if($name==''){
	error(2);
}
$cache_file_path = cacheFilePath('chaojigonglue').$name.'.txt';
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
$sql_news = 'select title, archive_publish_time, html_path, html_file, redriecturl '
	.'from joyme_point_archive WHERE spec_file_path="'.$game[0]['file_path'].'" limit 1000';
$resArr = msg($sql_news);

$news = array();
foreach($resArr as $k=>$v){
	$news[$k]['title'] = $v['title'];
	if(empty($news[$k]['redriecturl'])){
		$news[$k]['url'] = 'http://marticle.joyme.'.$com.'/marticle/'.$v['html_path'].'/'.$v['html_file'];
	}else{
		$news[$k]['url'] = $news[$k]['redriecturl'];
	}
	$news[$k]['time'] = date('Y-m-d', ($v['archive_publish_time']/1000));
}
$str_json = JSON(array('article'=>$news));
$res = file_put_contents($cache_file_path, $str_json);
if($res){
	echo $str_json;
}else{
	error(3);
}