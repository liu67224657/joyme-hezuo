<?php

// 趣图tag标签接口公共函数库

function index(){
	$data = getData();
	getColumnInfo();
	$bodys = getTagBody($data);
	makeJson($data, $bodys, true);
}

function getTagBody($data){
	global $dbr;
	$ids = array();
	foreach($data as $row){
		$ids[] = $row['id'];
	}
	$sql = 'SELECT aid, imgurls FROM dede_addonimages WHERE aid IN ('.implode(',', $ids).')';
	$bodys = $dbr->getRows($sql);
	$res = array();
	foreach($bodys as $row){
		$res[$row['aid']] = $row['imgurls'];
	}
	return $res;
}

function getTagImgList($content){
	$res = array();
	preg_match_all("/{dede:img.*?ddimg='(.*?)'.*?width='(.*?)'.*?height='(.*?)'.*?{\/dede:img}/is", $content, $match);
	if(empty($match[1])){
		return array();
	}else{
		foreach($match[1] as $k=>$src){
			$src = getImg($src);
			$imgsum = count($res);
			if($imgsum >= 3 ){
				break;
			}else{
				$imginfo = array('url'=>$src);
				if(!empty($match[2][$k])){
					$imginfo['width'] = $match[2][$k];
				}
				if(!empty($match[3][$k])){
					$imginfo['height'] = $match[3][$k];
				}
				$res[] = $imginfo;
			}
		}
		return $res;
	}
}