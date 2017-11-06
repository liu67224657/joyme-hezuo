<?php

// 栏目接口公共函数库

function index(){
	global $cat, $isnew;
	$data = getCatData();
	$bodys = getCatBody($data);
	makeJson($data, $bodys);
}

function getCatData(){
	global $dbr, $cat, $isnew, $now, $expiration;
	if($isnew){
		$sql = 'SELECT id,typeid,title,pubdate,senddate,description FROM dede_archives WHERE typeid  = '.$cat.' AND ismake = 1 AND arcrank > -1 ORDER BY pubdate DESC LIMIT 150';
	}else{
		$sql = 'SELECT id,typeid,title,pubdate,senddate,description FROM dede_archives WHERE typeid  = '.$cat.' AND ismake = 1 AND arcrank > -1 AND pubdate >= '.($now-$expiration).' ORDER BY pubdate DESC';
	}
	return $dbr->getRows($sql);
}

function getCatBody($data){
	global $dbr, $cat, $columninfo;
	$sql = 'SELECT typedir,addtable,da.namerule FROM dede_arctype AS da LEFT JOIN dede_channeltype AS dc ON da.channeltype=dc.id WHERE da.id='.$cat.' LIMIT 1';
	$columninfo = $dbr->getRow($sql);
	$ids = array();
	foreach($data as $row){
		$ids[] = $row['id'];
	}
	$sql = 'SELECT aid, body FROM '.$columninfo['addtable'].' WHERE aid IN ('.implode(',', $ids).')';
	$bodys = $dbr->getRows($sql);
	$res = array();
	foreach($bodys as $row){
		$res[$row['aid']] = $row['body'];
	}
	return $res;
}

function getCatImgList( $content ){
	$res = array();
	preg_match_all('/<img.*?src="(.*?)".*?>/is', $content, $match);
	if(empty($match[1])){
		return array();
	}else{
		foreach($match[1] as $src){
			$src = getImg($src);
			if($src){
				$res[] = $src;
			}
			$imgsum = count($res);
			if($imgsum >= 3 ){
				break;
			}
		}
		if($imgsum==2){
			array_pop($res);
		}
		return $res;
	}
}