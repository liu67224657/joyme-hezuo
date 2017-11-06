<?php

// 百度爱玩--礼包

$BaiduHezuoModel = new BaiduHezuoModel();
$where = array('maincat'=>5, 'wikikey'=>$k);
$data = $BaiduHezuoModel->getData($where);
$json = '';
foreach($data as $val){
	$tmp = array(
		'indexData'=>$val['gamename'],
		'title'=>$val['arctitle'],
		'image'=>$val['litpic'],
		'url'=>$val['arcurl'],
		'pubtime'=>intval($val['pubdate']),
		'expiration'=>intval($val['validitytime']),
		'surplus'=>intval($val['surplus']),
	);
	$json .= json_encode($tmp)."\n";
}

echo $json;



