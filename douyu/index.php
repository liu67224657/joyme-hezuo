<?php

// 斗鱼直播合作
define( 'DS' , DIRECTORY_SEPARATOR );
define( 'AROOT' , dirname( __FILE__ ) . DS  );

include('common.php');
include('DouyuHezuoModel.php');

use Joyme\core\Request;
use Joyme\net\Curl;

$k = Request::get('k', '');
$callback = Request::get('callback', '');
$wikikey = Request::get('wikikey', '');
$uid = $_COOKIE['jmuc_u'];

if($wikikey && $uid){
	$userid = Request::get('userid', 0);
	$roomid = Request::get('roomid', 0);
	$nickname = Request::get('nickname', '');
	$qq = Request::get('qq', 0);
	$cellphone = Request::get('cellphone', '');
	$userdesc = Request::get('userdesc', '');
	if($userid == 0 || $roomid ==0 || $nickname == '' || $qq == 0 || $cellphone == '' || $userdesc == '' || !preg_match("/^1[34578]{1}\d{9}$/",$cellphone) || !preg_match("/^[1-9][0-9]{5,9}$/",$qq) || !preg_match("/^[a-z]+[\w1-9]+$/",$wikikey)){
		echo $callback.'('.json_encode(array('error'=>1, 'msg'=>'数据错误')).')';
	}else{
		$data = array(
			'userid' => intval($userid),
			'roomid' => intval($roomid),
			'nickname' => $nickname,
			'qq' => $qq,
			'cellphone' => $cellphone,
			'userdesc' => $userdesc,
			'wikikey' => $wikikey,
			'ctime' => time(),
			'userstatus' => 0,
		);
		$douyuHezuoModel = new DouyuHezuoModel();
        $ret = $douyuHezuoModel->insert($data);
		echo $callback.'('.json_encode(array('error'=>0, 'msg'=>'提交成功，请耐心等待审核')).')';
	}
	exit;
}else if($wikikey){
	echo $callback.'('.json_encode(array('error'=>2, 'msg'=>'请登录后申请')).')';exit;
}

// 读取列表
if($k == ''){
	echo $callback.'('.json_encode(array('error'=>0, 'msg'=>'参数错误')).')';exit;
}
$expiration = 300; // 过期时间
$s = AROOT . 'cache/'.$k. '.json'; // 缓存文件

if( file_exists( $s ) && (filemtime( $s )>time()-$expiration) ){
	echo file_get_contents($s);
}else{
	$Douyu = new DouyuHezuoModel();
	$where = array('userstatus'=>1, 'islock'=>0, 'wikikey'=>$k);
	$data = $Douyu->select('roomid,rank', $where, 'rank DESC', null);
	$list = array();
	foreach($data as $key=>$val){
		if($val['roomid']){
			$curl = new Curl();
			$json = $curl->Get('http://open.douyucdn.cn/api/RoomApi/room/'.$val['roomid']);
			$res = json_decode($json, true);
			if($res['error'] == 0){
				$res['data']['rank'] = $val['rank'];
				unset($res['data']['gift']);
				$list[] = $res['data'];
			}
		}
	}
	$str = $callback.'('.json_encode(array('error'=>0, 'list'=>$list)).')';
	file_put_contents($s, $str);
	echo $str;
}

