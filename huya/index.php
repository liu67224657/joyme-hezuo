<?php

// 虎牙直播合作
// http://www.huya.com/cache.php?m=Game&do=ajaxGameLiveByPage&gid=2336&page=4&pageNum=1
define( 'DS' , DIRECTORY_SEPARATOR );
define( 'AROOT' , dirname( __FILE__ ) . DS  );

include('common.php');

use Joyme\core\Request;
use Joyme\net\Curl;

$gid = Request::get('gid', 0);
$pno = Request::get('page', 1);
$callback = Request::get('callback', '');
if($gid == 0){
	echo $callback.'('.json_encode(array('code'=>1, 'message'=>'游戏不存在', 'data'=>array())).')';
	exit;
}
$expiration = 300; // 过期时间
$s = AROOT . 'cache/'.$gid.'_'.$pno. '.json'; // 缓存文件
if( file_exists( $s ) && (filemtime( $s )>time()-$expiration) ){
	echo file_get_contents($s);
}else{
	$curl = new Curl();
	$url = $api.'?m=Game&do=ajaxGameLiveByPage&gid='.$gid.'&page='.$pno;
	$json = $curl->Get($url);
	$res = json_decode($json, true);
	if($res['status'] == 200){
		$str = $callback.'('.json_encode(array('code'=>0, 'message'=>'success', 'data'=>$res['data'])).')';
		file_put_contents($s, $str);
		echo $str;
	}else{
		$str = $callback.'('.json_encode(array('code'=>$res['status'], 'message'=>$res['message'], 'data'=>$res['data'])).')';
		echo $str;
	}
}

