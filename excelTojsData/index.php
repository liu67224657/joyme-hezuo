<?php
header('Content-type:text/html;charset=utf-8');

include("helper.php");
$ob = empty($_GET['ob']) ? false : $_GET['ob'];

/*************ls 数据生成**************/
if($ob==='ls'){
	$cards_filepath = "data/cards.xls";
	$class_filepath = "data/class.xlsx";
	$effect_filepath = "data/effect.xlsx";

	$cards = parse_excel($cards_filepath);
	$class = parse_excel($class_filepath);
	$effect = parse_excel($effect_filepath);

	$cards = key_replace($cards);
	$class = key_replace($class);
	$effect = key_replace($effect);

	$effect_data = array();
	foreach($effect as $val){
		$effect_data[$val['enName']] = $val;
	}

	$str = 'window.data='.json_encode($cards).';window.option='.json_encode(array('effect'=>$effect_data, 'class'=>$class));
	file_put_contents('data/data.js', $str);
	echo '数据生成完毕';
	/*
	卡牌数据字段说明
	'序号' =>  'id',
	'职业' =>  'class',
	'名称' =>  'name',
	'消耗' =>  'mana',
	'类型' =>  'type',
	'攻击' =>  'attack',
	'声明' =>  'health',
	'效果' =>  'effect',
	'卡色' =>  'rarity',
	*/
}
/*************ms 数据生成**************/
else if($ob==='ms'){
	$ms_filepath = "data/ms.xlsx";
	$ms = parse_excel($ms_filepath);
	$ms = key_replace($ms);
	$str = 'window.data='.json_encode($ms).';';
	file_put_contents('data/msdata.js', $str);
	echo '数据生成完毕';
}
/*************mt2 数据生成**************/
else if($ob==='mt2'){
	$mt2_filepath = "data/mt2.xlsx";
	$mt2 = parse_excel($mt2_filepath);
	$mt2 = key_replace($mt2);
	$str = 'window.data='.json_encode($mt2).';';
	file_put_contents('data/mt2data.js', $str);
	echo '数据生成完毕';
}
/*************天天富翁随机页面 数据生成**************/
else if($ob==='ttfw'){
	$ttfw_filepath = "data/ttfw.xlsx";
	$ttfw = parse_excel($ttfw_filepath);
	$ttfw = key_replace($ttfw);
	$str = 'window.data='.json_encode($ttfw).';';
	file_put_contents('data/ttfwdata.js', $str);
	echo '数据生成完毕';
}
/*************天天富翁随机页面 数据生成**************/
else if($ob==='ldxy'){
	$ldxy_filepath = "data/ldxy.xlsx";
	$ldxy = parse_excel($ldxy_filepath);
	$ldxy = key_replace($ldxy);
	$str = 'window.data='.json_encode($ldxy).';';
	file_put_contents('data/ldxydata.js', $str);
	echo '数据生成完毕';
}
/*************只能数据生成**************/
else if($ob){
	$filepath = "data/$ob.xlsx";
	if(!file_exists($filepath)) die('文件不存在');
	$data = parse_excel($filepath);
	$data = key_replace($data);
	$str = 'window.data='.json_encode($data).';';
	file_put_contents("data/{$ob}data.js", $str);
	echo '数据生成完毕';
}
/*************没有项目可生成**************/
else{
	echo '没有指定参数或参数错误';
}


