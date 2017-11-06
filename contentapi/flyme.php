<?php
/**
*Flyme 阅读接口
*/
header('Content-type: text/html; Charset=utf-8');
error_reporting(E_ALL);
include 'helper.fn.php';
include 'cmsurl.fn.php';
$cache_file_path = cacheFilePath('news').'flyme'.'.txt';
if(file_exists($cache_file_path)){
	echo file_get_contents($cache_file_path);exit;
}
//连接数据库
include 'db.php';

//设置主域名
$domain = 'http://www.joyme.'.$com;
$article_domain = 'http://article.joyme.'.$com;

function index(){
	$fields = 'a.id, a.pubdate, a.title, a.shorttitle, a.description, a.litpic, a.typeid, a.writer, a.money, a.filename, '
		.'a.senddate, a.ismake, a.arcrank, b.body, c.namerule, c.typedir, c.moresite, c.siteurl, c.sitepath ';
	$sql_news = 'SELECT '.$fields.' FROM dede_archives a, dede_addon17_lanmu b, dede_arctype c '
		.'WHERE a.id=b.aid AND a.typeid=c.id AND ismake=1 AND showpc=1 AND a.typeid IN (235, 236, 237) ORDER BY a.id DESC LIMIT 50';//echo $sql_news;exit;
	$res = msg($sql_news);
	$data = array();
	foreach($res as $k=>$val){
		$url = GetFileUrl($val['id'],$val['typeid'],$val['senddate'],$val['title'],$val['ismake'],
			$val['arcrank'],$val['namerule'],$val['typedir'],$val['money'],
			$val['filename'],$val['moresite'],$val['siteurl'],$val['sitepath']);
		$data[$k]['title'] = $val['title'];
		$data[$k]['link'] = 'http://'.$url;
		$data[$k]['putDate'] = $val['pubdate'];
		$data[$k]['author'] = $val['writer'];
		$data[$k]['description'] = $val['description'];
		$data[$k]['content'] = src_replace($val['body']);
	}
	return $data;
}

$str_json = JSON(index());
$res = file_put_contents($cache_file_path, $str_json);
if($res){
	echo $str_json;
}else{
	error(3);
}

function src_replace($content){
	preg_match_all('/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png|\.jpeg]))[\'|\"].*?[\/]?>/', $content, $match);
	foreach($match[1] as $src){
		if(strstr($src, 'http')===false){
			$content = str_replace($src, 'http://article.joyme.'.$com.$src, $content);
		}
	}
	return $content;
}