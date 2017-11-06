<?php

// 百度爱玩--资料

$BaiduHezuoModel = new BaiduHezuoModel();
$where = array('maincat'=>1, 'wikikey'=>$k);
$data = $BaiduHezuoModel->getData($where);
$json = '';
foreach($data as $val){
	$tmp = array(
		'indexData'=>$val['gamename'],
		'title'=>$val['arctitle'],
		'image'=>$val['litpic'],
		'url'=>$val['arcurl'],
		'pubtime'=>intval($val['pubdate']),
		'category'=>$val['soncat'],
		'childCategory'=>''
	);
	$json .= json_encode($tmp)."\n";
}

echo $json;



