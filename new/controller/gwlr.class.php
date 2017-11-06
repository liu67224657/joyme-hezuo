<?php
/**
 * http://hezuo.joyme.alpha/new/?c=gwlr&a=index&userid=2
 * Created by PhpStorm.
 * 怪物猎人OL 我滴怪怪H5活动页面
 * User: Islander
 * Date: 2016/12/15
 * Time: 10:24
 
 *
 * 奖品类型说明 gifttype
 * 1. 银币	   15000  10000银币
 * 2. 烈魂石   1000   烈魂点*3
 * 3. 遗迹石板 100    遗迹石板*10
 * 4. 点券     10     5000点券
 
 *sql
 CREATE TABLE `active_gwlr_gift` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `cdk` varchar(32) NOT NULL COMMENT '中奖码',
  `gifttype` smallint(6) NOT NULL COMMENT '中奖类别',
  `userid` varchar(255) DEFAULT NULL COMMENT '玩家id',
  `gettime` int(11) DEFAULT NULL COMMENT '领取时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cdk` (`cdk`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'app.class.php');
use Joyme\core\Request;

class gwlr extends app{
	
	private $userid = 0;

    public function __construct(){}

    //首页展示
    public function index(){
		$this->userid = Request::getParam('userid', '');
		$gift = $this->getGift();
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {
			$this->errMsg('非微信端打开');
			exit;
		}else if( !$this->userid ){
			$this->errMsg('缺少必要参数:玩家ID');
			exit;
		}else if( !$gift ){
			// 奖品用完
			$res = array('code'=>2, 'msg'=>'no gift', 'data'=>array());
			echo $this->myecho($res);
			exit;
		}
		// 获奖信息
		$winmsg = $this->getWinMsg();
		if( !$winmsg ){
			// 还没有获奖
			$iswin = $this->isWin();
			if( !$iswin ){
				$res = array('code'=>0, 'msg'=>'success', 'data'=>array());
				echo $this->myecho($res);
				exit;
			}
			// 更新获奖用户
			$this->updateUserMsg( $gift['id'] );
			$gift['userid'] = $this->userid;
			$gift['gettime'] = time();
			$res = array('code'=>0, 'msg'=>'success', 'data'=>$gift);
		}else{
			// 已中奖用户
			$res = array('code'=>3, 'msg'=>'success', 'data'=>$winmsg);
		}
		echo $this->myecho($res);
    }
	// 是否中奖
	private function isWin(){
		$no = mt_rand(1,4);
		if($no == 4){
			return false;
		}else{
			return true;
		}
	}
	
	// 查询获奖记录
	private function getWinMsg(){
		$gwlr = M('ActiveGwlr');
		$where = array('userid'=>$this->userid);
		return $gwlr->selectRow('*', $where);
	}
	
	// 随机一个奖品
	private function getGift(){
		$gwlr = M('ActiveGwlr');
		$where = array();
		$order = 'rand()';
		return $gwlr->selectRow('*', $where, $order);
	}
	
	// 更新获奖用户信息
	private function updateUserMsg( $giftid ){
		$gwlr = M('ActiveGwlr');
		$where = array( 'id'=>$giftid );
		$data = array('userid'=>$this->userid, 'gettime'=>time());
		$gwlr -> update( $data, $where );
	}
	
	// 输出错误信息
	private function errMsg( $msg ){
		$res = array('code'=>1, 'msg'=>'error', 'data'=>$msg);
		echo $this->myecho($res);
	}
	
	private function myecho(array $data){
		if( !is_array($data) ){
			return;
		}
		$callbackfn = Request::getParam('callback', '');
		if( $callbackfn ){
			echo $callbackfn.'('. json_encode( $data ) .')';
		}else{
			echo json_encode( $data );
		}
	}
}
