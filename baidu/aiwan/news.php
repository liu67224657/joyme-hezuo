<?php

// 百度爱玩--资讯

$BaiduHezuoModel = new BaiduHezuoModel();
$where = array('maincat'=>2, 'wikikey'=>$k);
$data = $BaiduHezuoModel->getData($where);
$json = '';
foreach($data as $val){
	$tmp = array(
		'indexData'=>$val['gamename'],
		'title'=>$val['arctitle'],
		'image'=>$val['litpic'],
		'url'=>$val['arcurl'],
		'pubtime'=>intval($val['pubdate']),
		'category'=>$val['soncat']
	);
	$json .= json_encode($tmp)."\n";
}

echo $json;



