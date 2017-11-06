<?php
/**
*hao123资讯接口
*/
header('Content-type: text/html; Charset=utf-8');
error_reporting(E_ALL);
include 'helper.fn.php';
include 'cmsurl.fn.php';
$cache_file_path = cacheFilePath('news').'hao123_news'.'.txt';
if(file_exists($cache_file_path)){
	echo file_get_contents($cache_file_path);exit;
}
//连接数据库
include 'db.php';

$datetime = strtotime(date('Y-m-d', strtotime("-1 day")));//前一天零点时间戳
$ydatetime = strtotime(date('Y-m-d', time()));//昨天零点时间戳

$sql = 'SELECT *, a.id, a.description FROM dede_archives a, dede_addon17_lanmu b, dede_arctype c WHERE a.id=b.aid AND a.typeid=c.id AND ismake=1 AND showpc=1 AND a.pubdate > '.$datetime.' AND a.pubdate < '.$ydatetime.' AND a.typeid IN (236, 237, 240, 241) ORDER BY a.id DESC';
$res = mysql_query($sql);
$i=0;
$resArr = array();
while($row = mysql_fetch_assoc($res)){
	$url = GetFileUrl($row['id'],$row['typeid'],$row['senddate'],$row['title'],$row['ismake'],
	$row['arcrank'],$row['namerule'],$row['typedir'],$row['money'],$row['filename'],$row['moresite'],$row['siteurl'],$row['sitepath']);
	$resArr[$i]['title'] = $row['title'];
	preg_match('/《(.+)\》/', $row['title'], $match);
	if($match){
		$resArr[$i]['gamename'] = $match[1];
	}else{
		$resArr[$i]['gamename'] = '';
	}
	$resArr[$i]['cate'] = '着迷网';
	$resArr[$i]['cate_link'] = 'http://'.$url;
	$host = 'http://article.joyme.'.$com;
    $row["body"] = preg_replace("/src=\"\/article\/uploads/", "src=\"".$host."/article/uploads", $row["body"]);
	$body = preg_replace("'([\r\n])[\s]+'", "", $row['body']);
	$resArr[$i]['content'] = addslashes(str_replace("'", '"', $body));
	$resArr[$i]['create_time'] = $row['pubdate'];
	$i++;
}

$str_json = JSON($resArr);
$res = file_put_contents($cache_file_path, $str_json);
if($res){
	echo $str_json;
}else{
	error(3);
}