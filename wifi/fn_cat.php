<?php

// 栏目接口公共函数库

function index(){
	$data = getData();
	getColumnInfo();
	$bodys = getCatBody($data);
	makeJson($data, $bodys);
}

function getCatBody($data){
	global $dbr;
	$ids = array();
	foreach($data as $row){
		$ids[] = $row['id'];
	}
	$sql = 'SELECT aid, body FROM dede_addon17_lanmu WHERE aid IN ('.implode(',', $ids).')';
	$bodys = $dbr->getRows($sql);
	$res = array();
	foreach($bodys as $row){
		$res[$row['aid']] = $row['body'];
	}
	return $res;
}

function getCatImgList( $content ){
	$res = array();
	preg_match_all('/<img.*?src="(.*?)".*?width="(.*?)".*?height="(.*?)".*?>/is', $content, $match);
	preg_match_all('/<img.*?width="(.*?)".*?height="(.*?)".*?src="(.*?)".*?\/>/is', $content, $match2);
	if(empty($match[1]) && empty($match2[3])){
		return array();
	}else{
		if(!empty($match[1])){
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
		}
		if(!empty($match2[3])){
			foreach($match2[3] as $k=>$src){
				$src = getImg($src);
				$imgsum = count($res);
				if($imgsum >= 3 ){
					break;
				}else{
					$imginfo = array('url'=>$src);
					if(!empty($match2[1][$k])){
						$imginfo['width'] = $match2[1][$k];
					}
					if(!empty($match2[2][$k])){
						$imginfo['height'] = $match2[2][$k];
					}
					$res[] = $imginfo;
				}
			}
		}
		return $res;
	}
}