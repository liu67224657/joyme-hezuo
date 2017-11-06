<?php
use Joyme\net\Curl;
// 墨迹合作接口公共函数库

function getImg($src){
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
	global $domain, $cache, $joymelogo;
	if(empty($data)){
		$res = array('code'=>2, 'msg'=>'success', 'data_list'=>array());
		echo json_encode($res);
		exit;
	}
	$res = array(
		'code' 	=> 0,
		'msg'	=> 'success',
		'logo_img'	=> $joymelogo,
		'cooperation_type'	=> 0
	);
	$item = array();
	foreach($data as $row){
		$aid = $row['id'];
		$typeid = $row['typeid'];
		if($istag){
			$imglist = getTagImgList($bodys[$aid]);
		}else{
			$imglist = getCatImgList($bodys[$aid]);
		}
		if(empty($imglist)) continue;
		$pubdate = !empty($row['pubdate']) ? $row['pubdate'] : $row['senddate'];
		$url = getUrl($aid, $row['senddate'], $typeid);
		$item['title'] = $row['title'];
		$item['desc'] = $row['description'];
		$item['publish_date'] = date('Y-m-d H:i:s', $pubdate);
		$item['source'] = '着迷网';
		$item['tag'] = '游戏';
		$item['content_url'] = $domain.'/news/moji'.$url;
		$item['original_url'] = $domain.$url;
		$item['image_list'] = $imglist;
		$item['show_type'] = 3;
		$res['data_list'][] = $item;
		if(count($res['data_list'])>=100){
			break;
		}
	}
	$json = json_encode($res);
	file_put_contents($cache, $json);
	echo $json;
}

function getUrl($aid, $pubdate, $typeid=0){
	global $columninfo, $istag;
	if($istag){
		if(!empty($columninfo[$typeid])){
			$namerule = $columninfo[$typeid]['namerule'];
			$typedir = $columninfo[$typeid]['typedir'];
		}else{
			return '';
		}
	}else{
		if(!empty($columninfo['namerule']) && !empty($columninfo['typedir'])){
			$namerule = $columninfo['namerule'];
			$typedir = $columninfo['typedir'];
		}else{
			return '';
		}
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