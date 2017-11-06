<?php
use Joyme\net\Curl;
// 墨迹合作接口公共函数库

function getData(){
	global $dbr, $cat,$page,$limit;
	
	$limitvalue = ($page-1)*$limit;
	
	$sql = 'SELECT id,typeid,title,pubdate,description,senddate FROM dede_archives WHERE typeid  = '.$cat.' AND ismake = 1 AND arcrank > -1 ORDER BY pubdate DESC LIMIT '.$limitvalue.','.$limit;
	return $dbr->getRows($sql);
}

function getColumnInfo(){
	global $dbr, $cat, $columninfo;
	$sql = 'SELECT typedir,addtable,da.namerule FROM dede_arctype AS da LEFT JOIN dede_channeltype AS dc ON da.channeltype=dc.id WHERE da.id='.$cat.' LIMIT 1';
	$columninfo = $dbr->getRow($sql);
}

function getImg($src){
	return $src;
	if(strpos($src, '?') !== false){
		$url = substr($src, 0, strpos($src, '?')).'?imageInfo';
	}else{
		$url = $src.'?imageInfo';
	}
	$curl = new Curl();
	$content = $curl->Get($url);
	$data = json_decode($content, true);
	$ratio = $data['width']/$data['height'];
	
	if($ratio>=0.8 && $ratio<=2 && $data['width']>=200 && $data['height']>=150){
		if( $data['format'] == 'gif' ){
			return $src.'?imageMogr2/format/jpg';
		}else{
			return $src;
		}
	}
	return '';
}

function makeJson($data, $bodys, $istag=false){
	global $domain, $cache, $joymelogo, $cat, $channle;
	
	$wifidata = array(
		'channelId'=>$cat,
		'channelName'=>$channle[$cat]
	);
	if(empty($data)){
		$wifidata['articles'] = array();
	}else{
		$item = array();
		foreach($data as $row){
			$aid = $row['id'];
			$typeid = $row['typeid'];
			if($istag){
				$imglist = getTagImgList($bodys[$aid]);
				$content = $row['description'];
				$tags = array('趣图');
			}else{
				$imglist = getCatImgList($bodys[$aid]);
				$content = $bodys[$aid];
				$tags = array('游戏');
			}
			if(empty($imglist)) $imglist = [];
			$pubdate = !empty($row['pubdate']) ? $row['pubdate'] : $row['senddate'];
			$url = getUrl($aid, $row['senddate'], $typeid);
			
			$item['newsId'] = $row['id'];
			$item['time'] = date('Y-m-d H:i:s', $row['senddate']);
			$item['updateTime'] = date('Y-m-d H:i:s', $pubdate);
			$item['title'] = $row['title'];
			$item['media'] = '着迷网';
			$item['original'] = 1;
			$item['only'] = 1;
			$item['region'] = '';
			$item['commentNum'] = 0;
			$item['description'] = $row['description'];
			$item['content'] = $content;
			$item['ctext'] = strip_tags($content);
			$item['images'] = $imglist;
			$item['tags'] = $tags;
			$item['newsUrl'] = $domain.'/news/wifi'.$url;
			$item['newsUrlHttps'] = '';
			$wifidata['articles'][] = $item;
		}
	}
	
	$json = json_encode($wifidata,JSON_UNESCAPED_UNICODE);
	file_put_contents($cache, $json);
	echo $json;
}

function getUrl($aid, $pubdate, $typeid=0){
	global $columninfo, $istag;
	if(!empty($columninfo['namerule']) && !empty($columninfo['typedir'])){
		$namerule = $columninfo['namerule'];
		$typedir = $columninfo['typedir'];
	}else{
		return '';
	}
	$url = $namerule;
	$Y = date('Y', $pubdate);
	$M = date('m', $pubdate);
	$D = date('d', $pubdate);

	$url = str_replace('{typedir}', $typedir, $url);
	$url = str_replace('{Y}', $Y, $url);
	$url = str_replace('{M}', $M, $url);
	$url = str_replace('{D}', $D, $url);
	$url = str_replace('{aid}', $aid, $url);
	$url = str_replace('{cmspath}', '', $url);
	return $url;
}

function err($msg){
	$res = array('code'=>1, 'msg'=>$msg, 'data_list'=>array());
	echo json_encode($res);
}