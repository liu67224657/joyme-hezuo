<?php

#sougou_hezuo#
// header("Content-type: text/html; charset=utf-8");
header("Content-type: text/xml;");
define( 'DS' , DIRECTORY_SEPARATOR );
define( 'AROOT' , dirname( __FILE__ ) . DS  );

include('common.php');
use Joyme\core\Request;
include('SougouHezuoPcModel.php');
$sougougame = new SougouHezuoPcGameModel();
$sougouarticle = new SougouHezuoArticlePcModel();

$enmaincats = array(1=>'news',2=>'strategy', 3=>'video');
$games = $sougougame->getData();
$gameids = array();
foreach($games as $row){
	$gameids[] = $row['id'];
}

$articles = $sougouarticle->select('*', '`status`=1 AND gameid in ('.implode(',', $gameids).')', 'pubdate DESC', null);
// echo $sougouarticle->getQuerySql();exit;
$arcdata = array();
foreach($articles as $row){
	$arcdata[$row['gameid']][$row['maincat']][] = $row;
}
// var_dump($arcdata);exit;
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
	global $maincats, $enmaincats;
	
	if(!is_array($item) || empty($item)){
		return;
	}
	$tab = $display->addChild('tab');
	$tab->addAttribute('content', $enmaincats[$key]);
	if($key == 2){
		$tab->addAttribute('current', 1);
	}else{
		$tab->addAttribute('current', 0);
	}
	foreach($item as $row){
		$form = $tab->addChild('form');
		$form->addChild('title', $row['title']);
		$form->addChild('link', $row['arcurl']);
		$form->addChild('rawcoverimage', $row['litpic']);
		$form->addChild('abstract', $row['arcdesc']);
		$form->addChild('keywords', $row['keyword']);
		$form->addChild('time', date('Y-m-d', $row['pubdate']));
		if($key == 3){
			$form->addChild('duration', $row['duration']);
		}
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
	$gametype = $game['gametype']==1 ? 'ONLINEGAME' : 'PCGAME';
	$item->addChild('game', $game['gamename']);
	$item->addChild('gametype', $gametype);
	$item->addChild('url', $game['wikiurl']);
	// '1'=>'资讯','2'=>'攻略','3'=>'视频','4'=>'礼包'
	addGameItem($game, $item);
}
echo $xml->asXML();

?>