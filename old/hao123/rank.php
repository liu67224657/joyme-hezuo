<?php if ( !defined('JOYME') ) exit('No direct script access allowed');

$table = 'hao123_rank';
$cachePath = './cache/';
$pArr = array('add', 'addPro', 'update', 'updatePro', 'rankList', 'del');
$cat_en = array('joyme_pc', 'joyme_mmo');
if( in_array($p, $pArr)){
	$p();
}elseif( $p=='joyme_pc' || $p=='joyme_mmo' ){
	cacheData($p);
}else{
	exit('Object does not exist');
}

function add(){
	include 'hao123.php';
}

function addPro(){
	global $table;
	global $cat_en;
	$data = getData();
	$res = insertData($table, $data);
	cacheData($cat_en[$data['cat']], true);
	if($res){
		echo '数据添加成功，<a href="index.php?m=rank&p=rankList">查看列表</a>';
	}else{
		echo '数据添加失败，<a href="index.php?m=rank&p=add">重新添加</a>';
	}
}

function update(){
	global $table;
	$id = intval($_GET['id']);
	$where = ' WHERE id = '.$id;
	$data = selectData($table, $where);
	include 'hao123.php';
}

function updatePro(){
	global $table;
	global $cat_en;
	$data = getData();
	$id = intval($_POST['id']);
	$where = ' WHERE id = '.$id;
	$res = updateData($table, $data, $where);
	cacheData($cat_en[$data['cat']], true);
	if($res){
		echo '数据更新成功，<a href="index.php?m=rank&p=rankList">查看列表</a>';
	}else{
		echo '数据更新失败，<a href="index.php?m=rank&p=update&id='.$id.'">重新修改</a>';
	}
}

function rankList(){
	global $table;
	$cat = array('网游', '单机');
	$where = ' WHERE 1=1 order by serial';
	$data = selectData($table, $where);
	include 'rankList.php';
}

function del(){
	global $table;
	$id = intval($_GET['id']);
	$where = ' WHERE id = '.$id;
	$res = delData($table, $where);
	if($res){
		echo '数据删除成功，<a href="index.php?m=rank&p=rankList">查看列表</a>';
	}else{
		echo '数据删除失败，<a href="index.php?m=rank&p=rankList">查看列表</a>';
	}
}

function getData(){
	return array(
		'title'		=> addslashes($_POST['title']),
		'url'		=> addslashes($_POST['url']),
		'img_url'	=> addslashes($_POST['img_url']),
		'other'	=> addslashes($_POST['other']),
		'cat'		=> intval($_POST['cat']),
		'serial'	=> intval($_POST['serial']),
	);
}

function joyme_pc(){
	global $table;
	$where = ' WHERE cat = 1 order by serial limit 10';
	$data = selectData($table, $where);
	$res = recombination($data);
	return json_encode(array('joyme_pc'=>$res));
}

function joyme_mmo(){
	global $table;
	$where = ' WHERE cat = 0 order by serial limit 10';
	$data = selectData($table, $where);
	$res = recombination($data);
	return json_encode(array('joyme_mmo'=>$res));
}

//最后数据重组，去掉多余字段
function recombination($data){
	$rank = array();
	foreach($data as $k=>$v){
		$rank[] = array_slice($v, 1, 4);
	}
	return $rank;
}

//缓存刷新--刷新机制，再添加或修改时候调用该函数，设置$clear参数值为true
function cacheData( $tag, $clear=false ){
	global $cachePath;
	$cacheFile = $cachePath.$tag.'.txt';
	if($clear){
		@unlink($cacheFile);
	}
	if(file_exists($cacheFile)){
		echo file_get_contents($cacheFile);exit;
	}
	$res = $tag();
	file_put_contents($cacheFile, $res);
	if(!$clear){
		echo $res;
	}
}