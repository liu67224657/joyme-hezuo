<?php

// 趣图tag标签接口公共函数库

function index(){
	$data = getTagData();
	$bodys = getTagBody($data);
	makeJson($data, $bodys, true);
}

function getTagData(){
	global $dbr, $cat, $isnew, $now, $expiration;
	if($isnew){
		$sql = 'SELECT id,da.typeid,title,senddate,description,da.typeid FROM dede_archives AS da LEFT JOIN dede_taglist AS dt ON da.id=dt.aid WHERE dt.tid = '.$cat.' AND da.arcrank >-1 AND ismake=1 ORDER BY da.id DESC LIMIT 150';
	}else{
		$sql = 'SELECT id,da.typeid,title,senddate,description,da.typeid FROM dede_archives AS da LEFT JOIN dede_taglist AS dt ON da.id=dt.aid WHERE dt.tid = '.$cat.' AND da.arcrank >-1 AND ismake=1 AND pubdate >= '.($now-$expiration).' ORDER BY da.id DESC';
	}
	return $dbr->getRows($sql);
}

function getTagBody($data){
	global $dbr, $cat, $columninfo;
	getColumnInfo();
	// $sql = 'SELECT typeid FROM dede_tagindex WHERE id = '.$cat;
	// $tagtype = $dbr->getRow($sql);
	// $cat = $tagtype['typeid'];
	// $sql = 'SELECT typedir,addtable,da.namerule FROM dede_arctype AS da LEFT JOIN dede_channeltype AS dc ON da.channeltype=dc.id WHERE da.id='.$cat.' LIMIT 1';
	// $columninfo = $dbr->getRow($sql);
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

function getColumnInfo(){
	global $dbr, $columninfo;
	$sql = 'SELECT `value` FROM dede_sysconfig WHERE varname = "cfg_type_list"';
	$typeids = $dbr->getRow($sql);
	$sql = 'SELECT id,typedir,namerule FROM dede_arctype WHERE id IN ('.str_replace('|', ',', $typeids['value']).');';
	$typeinfo = $dbr->getRows($sql);
	foreach($typeinfo as $val){
		$columninfo[$val['id']] = $val;
	}
}

function getTagImgList($content){
	$res = array();
	preg_match_all("/{dede:img.*?ddimg='(.*?)'.*?{\/dede:img}/is", $content, $match);
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