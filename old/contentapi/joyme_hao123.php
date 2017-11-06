<?php
/**
*hao123资讯接口
*/
header('Content-type: text/html; Charset=utf-8');
error_reporting(0);
include 'helper.fn.php';
include 'cmsurl.fn.php';
$cache_file_path = cacheFilePath('news').'joyme_hao123'.'.txt';
if(file_exists($cache_file_path)){
	echo file_get_contents($cache_file_path);exit;
}
//连接数据库
include 'db.php';

//设置主域名
$domain = 'http://www.joyme.'.$com;
$article_domain = 'http://article.joyme.'.$com;

function index(){
	return array(
		'recommend'	=> array(),
		'hot'		=> array(),
		'news'		=> array(
			'first'		=> first(),
			'normal'	=> normal(),
			),
		'comment'	=> comment(),
	);
}
echo JSON(index());exit;
$str_json = JSON(index());echo $str_json;
$res = file_put_contents($cache_file_path, $str_json);
if($res){
	echo $str_json;
}else{
	error(3);
}
/**
*精彩资讯，news分类共有2类：1.推荐位，1个；2.精彩资讯，7个
*字段1：name, link, pic, intro(40); 字段2：name, link; 
*游戏资讯237，日韩240，欧美241，精品推荐243，
*/

//推荐位
function first(){
	global $article_domain;
	$fields = 'a.id, a.pubdate, a.title, a.shorttitle, a.description, a.litpic, a.typeid, '
		.'a.senddate, a.ismake, a.arcrank, b.pingfen, c.namerule, c.typedir, a.money, a.filename, c.moresite, c.siteurl, c.sitepath';
	$sql_news = 'SELECT '.$fields.' FROM dede_archives a, dede_addon17_lanmu b, dede_arctype c '
		.'WHERE a.id=b.aid AND a.typeid=c.id AND ismake=1 AND showpc=1 AND a.typeid = 766 ORDER BY a.id DESC LIMIT 1';
	$commentdata = msg($sql_news);
	$firstdata = $commentdata[0];
	$url = GetFileUrl($firstdata['id'],$firstdata['typeid'],$firstdata['senddate'],$firstdata['title'],$firstdata['ismake'],
		$firstdata['arcrank'],$firstdata['namerule'],$firstdata['typedir'],$firstdata['money'],
		$firstdata['filename'],$firstdata['moresite'],$firstdata['siteurl'],$firstdata['sitepath']);
		
	return array(
		'name'	=> $firstdata['shorttitle'],
		'link'	=> 'http://'.$url,
		'pic'	=> $article_domain.$firstdata['litpic'],
		'intro'	=> $firstdata['description'],
	);
}

//精彩资讯
function normal(){
	$fields = 'a.id, a.pubdate, a.title, a.shorttitle, a.description, a.litpic, a.typeid, '
		.'a.senddate, a.ismake, a.arcrank, c.namerule, c.typedir, a.money, a.filename, c.moresite, c.siteurl, c.sitepath';
	$sql_news = 'SELECT '.$fields.' FROM dede_archives a, dede_addon17_lanmu b, dede_arctype c '
		.'WHERE a.id=b.aid AND a.typeid=c.id AND ismake=1 AND showpc=1 AND a.typeid IN (237, 240, 241, 243) ORDER BY a.id DESC LIMIT 7';//echo $sql_news;exit;
	$normaldata = msg($sql_news);
	$resoultarr = array();
	foreach($normaldata as $k=>$val){
		$url = GetFileUrl($val['id'],$val['typeid'],$val['senddate'],$val['title'],$val['ismake'],
			$val['arcrank'],$val['namerule'],$val['typedir'],$val['money'],
			$val['filename'],$val['moresite'],$val['siteurl'],$val['sitepath']);
		$resoultarr[$k]['name'] = $val['title'];
		$resoultarr[$k]['link'] = 'http://'.$url;
	}
	return $resoultarr;
}

/**
*新游评测，comment共有4个元素
*字段：name, link, pic, intro(20), score
*游戏评测236
*/
function comment(){
	global $article_domain;
	$fields = 'a.id, a.pubdate, a.title, a.shorttitle, a.description, a.litpic, a.typeid, '
		.'a.senddate, a.ismake, a.arcrank, b.pingfen, c.namerule, c.typedir, a.money, a.filename, c.moresite, c.siteurl, c.sitepath';
	$sql_news = 'SELECT '.$fields.' FROM dede_archives a, dede_addon17_lanmu b, dede_arctype c '
		.'WHERE a.id=b.aid AND a.typeid=c.id AND ismake=1 AND showpc=1 AND a.typeid = 236 ORDER BY a.id DESC LIMIT 4';//echo $sql_news;exit;
	$commentdata = msg($sql_news);
	$resoultarr = array();
	foreach($commentdata as $k=>$val){
		if($val['pingfen']==0){
			$pingfen = 1.0;
		}else if($val['pingfen']==10){
			$pingfen = $val['pingfen'];
		}else{
			$pingfen = number_format($val['pingfen'],1);
		}
		
		$url = GetFileUrl($val['id'],$val['typeid'],$val['senddate'],$val['title'],$val['ismake'],
			$val['arcrank'],$val['namerule'],$val['typedir'],$val['money'],
			$val['filename'],$val['moresite'],$val['siteurl'],$val['sitepath']);
		$resoultarr[$k]['name']		= $val['shorttitle'];
		$resoultarr[$k]['link']		= 'http://'.$url;
		$resoultarr[$k]['pic']		= 'http://hezuo.joyme.'.$com.'/contentapi/images/'.$pingfen.'.jpg';
		$resoultarr[$k]['intro']	= $val['description'];
		$resoultarr[$k]['score']	= $pingfen;
	}
	return $resoultarr;
}

