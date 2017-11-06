<?php

#sougou_hezuo#
// header("Content-type: text/html; charset=utf-8");
header("Content-type: text/xml;");
define( 'DS' , DIRECTORY_SEPARATOR );
define( 'AROOT' , dirname( __FILE__ ) . DS  );

include('common.php');
include('SougouHezuoModel.php');

$sougougame = new SougouHezuoGameModel();
$sougouarticle = new SougouHezuoArticleModel();

$games = $sougougame->getData();
$gameids = array();
foreach($games as $row){
	$gameids[] = $row['id'];
}

$articles = $sougouarticle->select('*', '`status`=1 AND gameid in ('.implode(',', $gameids).')', 'pubdate DESC', null);

$arcdata = array();
foreach($articles as $row){
	$arcdata[$row['gameid']][$row['maincat']][] = $row;
}

$string = <<<XML
<?xml version='1.0' encoding='GBK'?>
<DOCUMENT>
</DOCUMENT>
XML;
$xml = simplexml_load_string($string);

function addGameItem($game, $display){
	global $arcdata;
	$gamedata = $arcdata[$game['id']];
	if(!is_array($gamedata) || empty($gamedata)){
		return;
	}

	foreach($gamedata as $key=>$item){
		if($key == 1){//资讯
			commontab($item, $display, $key, $game['catlink1']);
		}else if($key == 2){//攻略
			commontab($item, $display, $key, $game['catlink2']);
		}else if($key == 3){//视频
			commontab($item, $display, $key, $game['catlink3']);
		}else if($key == 4){//礼包
			gifttab($item, $display, $key, $game['catlink4']);
		}
	}
}

//视频，攻略，资讯
function commontab($item, $display, $key, $moreurl){
	global $maincats;
	if(!is_array($item) || empty($item)){
		return;
	}
	$tab = $display->addChild('tab');
	$tab->addAttribute('content', $maincats[$key]);
	if($key == 2){
		$tab->addAttribute('current', 1);
	}else{
		$tab->addAttribute('current', 0);
	}
	foreach($item as $row){
		$form = $tab->addChild('form');
		$form->addChild('name', $row['title']);
		$form->addChild('link', $row['arcurl']);
		$form->addChild('time', date('Y-m-d', $row['pubdate']));
		$form->addChild('picture', $row['litpic']);
	}
	$morelink = $tab->addChild('morelink');
	$morelink->addAttribute('linkurl', $moreurl);
	$morelink->addAttribute('linkcontent', '查看更多'.$maincats[$key]);
}

//礼包
function gifttab($item, $display, $key, $moreurl){
	global $maincats;
	if(!is_array($item) || empty($item)){
		return;
	}
	$tab = $display->addChild('tab');
	$tab->addAttribute('content', $maincats[$key]);
	$tab->addAttribute('current', 0);
	foreach($item as $row){
		$form = $tab->addChild('form');
		$form->addChild('name', $row['title']);
		$form->addChild('state', '领取');
		$form->addChild('link', $row['arcurl']);
		$form->addChild('startime', date('Y-m-d', $row['pubdate']));
		$form->addChild('endtime', date('Y-m-d', $row['expiration']));
	}
	$morelink = $tab->addChild('morelink');
	$morelink->addAttribute('linkurl', $moreurl);
	$morelink->addAttribute('linkcontent', '查看更多'.$maincats[$key]);
}

foreach($games as $game){
	$item = $xml->addChild('item');
	$item->addChild('key', $game['gamename']);
	$display = $item->addChild('display');
	
	$display->addChild('game', $game['gamename']);
	$display->addChild('title', $game['gamename']);
	$display->addChild('url', $game['url']);
	$display->addChild('image', $game['litpic']);
	// '1'=>'资讯','2'=>'攻略','3'=>'视频','4'=>'礼包'
	addGameItem($game, $display);
	$item->addChild('showimg');
	$item->addChild('showname');
	$item->addChild('showurl');
	$item->addChild('rank', $game['rank']);
	$item->addChild('rankurl', $game['rankurl']);
	$item->addChild('guanwang', $game['guanwang']);
	$item->addChild('gametype', $game['gametype']);
	$item->addChild('gametypeurl', $game['gametypeurl']);
	$item->addChild('follow', $game['follow']);
	$item->addChild('iosurl', $game['iosurl']);
	$item->addChild('andurl', $game['andurl']);
	$item->addChild('pcurlr', $game['pcurl']);
}
echo $xml->asXML();

?>