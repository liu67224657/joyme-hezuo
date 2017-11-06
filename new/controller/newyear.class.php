<?php

/**
 * Description: 新年签到和抽奖机
 * Author: gradydong
 * Date: 2017/1/16
 * Time: 15:08
 * Copyright: Joyme.com
 */

if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'app.class.php');

use Joyme\core\Request;

class newyear extends app
{

    //***微信认证
    //AppID
    public $AppID = '';
    //AppSecret
    public $AppSecret = '';
    //access_token
    public $access_token = '';
    //openid
    public $openid = '';
    //回掉地址
    public $callback = '';

    public $shareJsPath = '';

    //密钥
    public $sign = '~(@jm@qyz!';

    //服务器列表数据获取接口
    public $getServerListUrl = '';

    //检测角色ID和服务器接口
    public $getCheckUserUrl = '';

    //ticket
    public $ticket = '';

    //怪物猎人签到礼品名称
    public $gwlrsigngifts = array(
        '1' => '银币*10000',
        '2' => '心眼药剂*3',
        '3' => '宗师砥石*3',
        '4' => '王立六星怪物素材包*5',
        '5' => '双倍素材券*3',
        '6' => '古龙精华*3',
        '7' => '105武器直升券',
        '8' => '梅杰波尔坦黄玉*3',
        '9' => '烈魂石*1',
    );

    //怪物猎人抽奖礼品名称
    public $gwlrlotterygifts = array(
        '100' => '收纳之书*10',
        '101' => '猫车票*5',
        '102' => '世界喇叭*3',
    );

    //御龙在天签到礼品名称
    public $ylztsigngifts = array(
        '1' => '换镖令*1',
        '2' => '中等淬炼宝石*1',
        '3' => '玄武血剂*1',
        '4' => '鸡毛令箭*1',
        '5' => '龙纹*1',
        '6' => '初级培养精华*1',
        '7' => '玫瑰花*1',
        '8' => '艺彤的拥抱*1',
        '9' => '紫色精铁*1',
    );
    //御龙在天抽奖礼品名称
    public $ylztlotterygifts = array(
        '100' => '黄色名将改造符',
        '101' => '绿色名将改造符',
        '102' => '金色麒麟30天',
    );

    //星际火线签到礼品名称
    public $xjhxsigngifts = array(
        '1' => '礼包',
        '2' => '礼包',
        '3' => '礼包',
        '4' => '礼包',
        '5' => '礼包',
        '6' => '礼包',
        '7' => '礼包',
        '8' => '礼包',
        '9' => '礼包',
        '15' => '礼包', // 1-5天签到
        '68' => '礼包', // 6-8天签到
    );

    //星际火线抽奖礼品名称
    public $xjhxlotterygifts = array(
        '100' => '88钻石',
        '101' => '枪械碎片箱X5',
        '102' => '288888金币',
    );

    //活动类型
    public $atypes = array(
        1,//怪物猎人
        2,//御龙在天
        3,//星际火线
    );


    function __construct()
    {
        //测试账号
        /*$this->AppID = 'wxe6eafdebe1a95bd5';
        $this->AppSecret = '51b24d3f0ad72cbaf3126cf6b615587c';*/

        //线上账号
        $this->AppID = 'wx28d67b05018d0f19';
        $this->AppSecret = 'dad223d60f6d9d6ee3ca7d4ce2326f5a';

        $this->shareJsPath = 'http://' . $_SERVER['HTTP_HOST'] . '/new/static/script/jweixin-1.1.0.js';

        parent::__construct();
    }

    //怪物猎人新年签到和抽奖页面
    public function gwlrindex()
    {
        $code = Request::getParam('code');
        if (empty($code)) {
            $code = $_COOKIE['code'];
            if (empty($code)) {
                $this->callback = 'http://' . $_SERVER['HTTP_HOST'] . '/new/?c=newyear&a=gwlrindex';
                $this->getUserCode();
            }
        }
        $openid = $this->getopenid($code);
        /*$code = 'codetest';
        $openid = 'openidtest';

        setcookie('code', $code, time() + 3600);
        setcookie('openid', $openid, time() + 3600);*/


        $data = array();
        $gwlr = new ActionNewyearGift1Model();

        $sign_day = $this->checksignday();
        if($sign_day < 9){
            $data["sign_day"] = $sign_day;
            $data["signed_day"] = $gwlr->count(array(
                'openid' => $openid,
                'status' => 2,
                'giftid' => array('in', array(1,2,3,4,5,6,7,8)),
                'atype' => 1
            ));
        }else{
            $data["sign_day"] = 8;
            $data["signed_day"] = $sign_day;
        }

        $signdays = $gwlr->select("giftid", array(
            'openid' => $openid,
            'status' => 2,
            'giftid' => array('in', array(1,2,3,4,5,6,7,8,9)),
            'atype' => 1
        ), '', '');
        if ($signdays) {
            $data["signdays"] = array_column($signdays, 'giftid');
        } else {
            $data["signdays"] = array();
        }

        $data['shareJsPath'] = $this->shareJsPath;
        $data['conf'] = $this->generate();

        render($data, 'web', 'newyear/gwlr');
    }

    //御龙在天新年签到和抽奖页面
    public function ylztindex()
    {
        $code = Request::getParam('code');
        if(empty($code)){
            $code = $_COOKIE['code'];
            if(empty($code)){
            $this->callback = 'http://' . $_SERVER['HTTP_HOST'] . '/new/?c=newyear&a=ylztindex';
                $this->getUserCode();
            }
        }
        $openid = $this->getopenid($code);

        /*$code = 'codetest';
        $openid = 'openidtest';

        setcookie('code', $code, time() + 3600);
        setcookie('openid', $openid, time() + 3600);*/

        $data = array();

        $gwlr = new ActionNewyearGift2Model();
        $data["signed_day"] = $gwlr->count(array(
            'openid' => $openid,
            'status' => 2,
            'giftid' => array('in', array(1,2,3,4,5,6,7,8,9)),
            'atype' => 2
        ));
        $sign_day = $this->checksignday();
        if($sign_day < 10){
            $data["sign_day"] = $sign_day;
            $data["ylzt_sign_day"] = $sign_day;
        }else{
            $data["sign_day"] = 9;
            $data["ylzt_sign_day"] = $sign_day;
        }


        $signdays = $gwlr->select("giftid", array(
            'openid' => $openid,
            'status' => 2,
            'giftid' => array('in', array(1,2,3,4,5,6,7,8,9)),
            'atype' => 2
        ), '', '');
        if ($signdays) {
            $data["signdays"] = array_column($signdays, 'giftid');
        } else {
            $data["signdays"] = array();
        }

        $data["ylztgiftname"] = $this->ylztsigngifts;

        $data['shareJsPath'] = $this->shareJsPath;
        $data['conf'] = $this->generate();


        render($data, 'web', 'newyear/ylzt');
    }

    //星际火线新年签到和抽奖页面
    public function xjhxindex()
    {
        $code = Request::getParam('code');
        if (empty($code)) {
            $code = $_COOKIE['code'];
            if (empty($code)) {
                $this->callback = 'http://' . $_SERVER['HTTP_HOST'] . '/new/?c=newyear&a=xjhxindex';
                $this->getUserCode();
            }
        }
        $openid = $this->getopenid($code);
        /*$code = 'codetest';
        $openid = 'openidtest';

        setcookie('code', $code, time() + 3600);
        setcookie('openid', $openid, time() + 3600);*/

        $data = array();
        $gift = new ActionNewyearGift3Model();
        $sign_day = $this->checksignday();
        if($sign_day < 9){
            $data["sign_day"] = $sign_day;
            $data["signed_day"] = $gift->count(array(
                'openid' => $openid,
                'status' => 2,
                'giftid' => array('in', array(15,68)),
                'atype' => 3
            ));
        }else{
            $data["signed_day"] = 8;
            $data["sign_day"] = 8;
        }
        $signdays = $gift->select("gettime", array(
            'openid' => $openid,
            'status' => 2,
            'giftid' => array('in', array(15,68)),
            'atype' => 3
        ), '', '');
        if ($signdays) {
            $gettimes = array_column($signdays, 'gettime');
            $xjhxsignday = array();
            foreach ($gettimes as $time){
                $xjhxsignday[] = $this->getxjhxsignday($time);
            }
            $data["signdays"] = $xjhxsignday;
        } else {
            $data["signdays"] = array();
        }

        $data['shareJsPath'] = $this->shareJsPath;
        $data['conf'] = $this->generate();

        render($data, 'web', 'newyear/xjhx');
    }

    private function getxjhxsignday($gettime){
        $datetime1 = date_create('2017-01-27');
        $datetime2 = date_create(date("Y-m-d",$gettime));
        $interval = date_diff($datetime1, $datetime2);
        return $interval->days + 1;
    }


    /**
     * 签到
     */
    public function sign()
    {
        $giftid = intval(Request::getParam('giftid'));
        $atype = intval(Request::getParam('atype'));
        if ($giftid && $atype) {
            if ($atype == 1) {
                $this->gwlrsign($giftid);
            } elseif ($atype == 2) {
                $this->ylztsign($giftid);
            } elseif ($atype == 3) {
                $this->xjhxsign($giftid);
            } else {
                $this->echojson(0, '参数不正确');
            }
        } else {
            $this->echojson(0, '参数不正确');
        }
    }

    /**
     * 怪物猎人签到
     */
    private function gwlrsign($giftid)
    {
        if ($this->checksignday() >= 9) {
            $this->echojson(3, '对不起，活动已结束！');
        }
        $openid = $_COOKIE['openid'];
        if ($giftid == 9) {
            $signcount = $this->getsignedday(1);
            if ($signcount != 8) {
                $this->echojson(2, '您未达到签到天数,请签满8天后再来');
            }
            $todayissign = $this->checkdayissign($giftid, 1);
            if (!empty($todayissign)) {
                $this->echojson(3, '您已获得的奖励请点击【我的礼包】查看');
            } else {
                $this->signrecord($openid, $giftid, 1);
            }
        } else {

            if ($giftid != $this->checksignday()) {
                $this->echojson(4, '只能领取签到当天的奖励哦！');
            }

            $todayissign = $this->checkdayissign($giftid, 1);
            if (!empty($todayissign)) {
                if ($giftid == $this->checksignday()) {
                    $this->echojson(5, '您已经领取过今日的奖励，请明日再来');
                } else {
                    $this->echojson(3, '您已获得奖励请点击【我的礼包】查看');
                }
            }
            $this->signrecord($openid, $giftid, 1);
        }
    }

    /**
     * 御龙在天签到
     */
    private function ylztsign($giftid)
    {
        $openid = $_COOKIE['openid'];
        if ($this->checksignday() >= 10) {
            $this->echojson(3, '对不起，活动已结束！');
        }

        if ($giftid != $this->checksignday()) {
            $this->echojson(3, '只能点亮今天的灯笼哦~');
        }

        $todayissign = $this->checkdayissign($giftid, 2);
        if (!empty($todayissign)) {
            if ($giftid == $this->checksignday()) {
                $this->echojson(5, '您已点亮今天的灯笼！<br>请明天再来！');
            } else {
                $this->echojson(3, '您已获得奖励<br>请点击【我的礼包】查看');
            }
        }
        $this->signrecord($openid, $giftid, 2);
    }

    /**
     * 星际火线签到
     */
    private function xjhxsign($giftid)
    {
        $openid = $_COOKIE['openid'];
        if ($this->checksignday() >= 10) {
            $this->echojson(0, '对不起，活动已结束！');
        }
        $sign_day = $giftid;
        $gift = new ActionNewyearGift3Model();
        if ($giftid == 9) {
            $signcount = $this->getsignedday(3);
            if ($signcount < 8) {
                $this->echojson(2, '您未达到签到天数,请签满8天后再来');
            }
            $begintime = strtotime ("+7 day", strtotime('2017-01-27 00:00:00'));
            $endtime = strtotime ("+7 day", strtotime('2017-01-28 00:00:00'));
            $todayissign = $gift->count(array(
                'openid' => $openid,
                'giftid' => 68,
                'status' => 2,
                'atype' => 3,
                'gettime' => array('between',array($begintime,$endtime))
            ));
            if (!empty($todayissign) && $todayissign==2) {
                $this->echojson(3, '您已获得的奖励请点击【我的礼包】查看');
            } else {
                //6-8天签到giftid=68
                $this->signrecord($openid, 68, 3);
            }
        } else {

            if ($giftid != $this->checksignday()) {
                $this->echojson(4, '只能领取签到当天的奖励哦！');
            }

            //1-5天签到giftid为15,6-8天签到giftid为68
            if($giftid <= 5){
                $giftid = 15;
            }
            elseif ($giftid>=6&&$giftid<=8){
                $giftid = 68;
            }

            $begintime = strtotime ("+".($sign_day-1)." day", strtotime('2017-01-27 00:00:00'));
            $endtime = strtotime ("+".($sign_day-1)." day", strtotime('2017-01-28 00:00:00'));
            $todayissign = $gift->count(array(
                'openid' => $openid,
                'giftid' => $giftid,
                'status' => 2,
                'atype' => 3,
                'gettime' => array('between',array($begintime,$endtime))
            ));
            if (!empty($todayissign)) {
                if ($sign_day == $this->checksignday()) {
                    $this->echojson(5, '您已经领取过今日的奖励，请明日再来');
                } else {
                    $this->echojson(3, '您已获得奖励请点击【我的礼包】查看');
                }
            }
            $this->signrecord($openid, $giftid, 3);
        }
    }


    /**
     * 抽奖机
     */
    public function lottery()
    {
        $atype = (int)Request::getParam('atype');
        if ($atype) {
            $openid = $_COOKIE['openid'];
            if ($atype == 1) {
                $this->gwlrlottery($openid);
            } elseif ($atype == 2) {
                $this->ylztlottery($openid);
            } elseif ($atype == 3) {
                $this->xjhxlottery($openid);
            }else{
                $this->echojson(0, '参数不正确');
            }
        } else {
            $this->echojson(0, '参数不正确');
        }
    }

    /**
     * 怪物猎人抽奖
     */
    private function gwlrlottery($openid)
    {
        if ($this->checksignday() >= 9) {
            $this->echojson(0, '对不起，活动已结束！');
        }
        $gift = new ActionNewyearGift1Model();
        $lotterygiftids = array_keys($this->gwlrlotterygifts);
        //判断是否已抽过奖
        $lotterycount = $gift->count(array(
            'openid' => $openid,
            'giftid' => array('in', $lotterygiftids),
            'atype' => 1
        ));
        if ($lotterycount) {
            $this->echojson(0, '已参加抽奖<br>您已获得奖励请点击【我的礼包】查看');
        }
        $signcount = $gift->count(array(
            'openid' => $openid,
            'atype' => 1
        ));
        //判断是否签到满五天
        if ($signcount < 5) {
            $this->echojson(0, '请签满五天再来');
        }
        //抽奖
        $gwlrcdk = $this->getwingiftcdk(1);
        if ($gwlrcdk) {
            $result = $gift->update(array(
                'openid' => $openid,
                'status' => 2,
                'gettime' => time()
            ), array(
                'id' => $gwlrcdk['id']
            ));
            if ($result) {
                $this->echojson(1, '恭喜中奖', array(
                    'code' => $gwlrcdk['giftid'],
                    'cdk' => $gwlrcdk['cdk'],
                    'name' => $this->gwlrlotterygifts[$gwlrcdk['giftid']]
                ));
            } else {
                $this->echojson(0, '领取失败');
            }
        } else {
            $this->echojson(0, 'CDK发放完毕');
        }
    }

    /**
     * 御龙在天抽奖
     */
    private function ylztlottery($openid)
    {
        if ($this->checksignday() >= 10) {
            $this->echojson(0, '对不起，活动已结束！');
        }

        $gift = new ActionNewyearGift2Model();
        $lotterygiftids = array_keys($this->ylztlotterygifts);
        //判断是否已抽过奖
        $lotterycount = $gift->count(array(
            'openid' => $openid,
            'giftid' => array('in', $lotterygiftids),
            'atype' => 2
        ));
        if ($lotterycount) {
            $this->echojson(0, '已参加抽奖');
        }
        $signcount = $gift->count(array(
            'openid' => $openid,
            'atype' => 2
        ));
        //判断是否签到满六天
        if ($signcount < 6) {
            $this->echojson(0, '请签满六天再来');
        }
        //抽奖
        $ylztcdk = $this->getwingiftcdk(2);
        if ($ylztcdk) {
            $result = $gift->update(array(
                'openid' => $openid,
                'status' => 2,
                'gettime' => time()
            ), array(
                'id' => $ylztcdk['id']
            ));
            if ($result) {
                $this->echojson(1, '恭喜中奖', array(
                    'code' => $ylztcdk['giftid'],
                    'cdk' => $ylztcdk['cdk'],
                    'name' => $this->ylztlotterygifts[$ylztcdk['giftid']]
                ));
            } else {
                $this->echojson(0, '领取失败');
            }
        } else {
            $this->echojson(0, 'CDK发放完毕');
        }
    }

    /**
     * 星际火线抽奖
     */
    private function xjhxlottery($openid)
    {
        if ($this->checksignday() >= 9) {
            $this->echojson(0, '对不起，活动已结束！');
        }
        $gift = new ActionNewyearGift3Model();
        $lotterygiftids = array_keys($this->xjhxlotterygifts);
        //判断是否已抽过奖
        $lotterycount = $gift->count(array(
            'openid' => $openid,
            'giftid' => array('in', $lotterygiftids),
            'atype' => 3
        ));
        if ($lotterycount) {
            $this->echojson(0, '已参加抽奖');
        }
        $signcount = $gift->count(array(
            'openid' => $openid,
            'atype' => 3
        ));
        //判断是否签到满五天
        if ($signcount < 5) {
            $this->echojson(0, '请签满五天再来');
        }
        //抽奖
        $xjhxcdk = $this->getwingiftcdk(3);
        if ($xjhxcdk) {
            $result = $gift->update(array(
                'openid' => $openid,
                'status' => 2,
                'gettime' => time()
            ), array(
                'id' => $xjhxcdk['id']
            ));
            if ($result) {
                $this->echojson(1, '恭喜中奖', array(
                    'code' => $xjhxcdk['giftid'],
                    'cdk' => $xjhxcdk['cdk']
                ));
            } else {
                $this->echojson(0, '领取失败');
            }
        } else {
            $this->echojson(0, 'CDK发放完毕');
        }
    }


    /**
     * 用户礼包
     */
    public function gift()
    {
        $atype = (int)Request::getParam('atype');
        if ($atype) {
            $openid = $_COOKIE['openid'];
			$className = 'ActionNewyearGift'.$atype.'Model';
            $gift = new $className();
            $gifts = $gift->select('cdk,giftid', array(
                'openid' => $openid,
                'status' => 2,
                'atype' => $atype
            ), '', '');
            if ($gifts) {
                $giftcdk = array();
                $nygifts = array();
                if ($atype == 1) {
                    $nygifts = $this->array_merge_ny($this->gwlrsigngifts, $this->gwlrlotterygifts);
                } elseif ($atype == 2) {
                    $nygifts = $this->array_merge_ny($this->ylztsigngifts, $this->ylztlotterygifts);
                } elseif ($atype == 3) {
                    $nygifts = $this->array_merge_ny($this->xjhxsigngifts, $this->xjhxlotterygifts);
                }
                foreach ($gifts as $k => $gift) {
                    $giftcdk[$k]['giftname'] = $nygifts[$gift['giftid']];
                    $giftcdk[$k]['cdk'] = $gift['cdk'];
                }
                $this->echojson(1, $giftcdk);
            } else {
                $this->echojson(0, '没有礼包');
            }
        } else {
            $this->echojson(0, '参数不正确');
        }
    }

    /**
     * 签到获取cdk
     */
    private function signrecord($openid, $giftid, $atype)
    {
		$className = 'ActionNewyearGift'.$atype.'Model';
        $gift = new $className();
        $nygift = $gift->selectRow('id,cdk', array(
            'giftid' => $giftid,
            'status' => 1,
            'atype' => $atype
        ));
        if ($nygift) {
            $result = $gift->update(array(
                'openid' => $openid,
                'status' => 2,
                'gettime' => time()
            ), array(
                'id' => $nygift['id']
            ));
            if ($result) {
                $giftname = '';
                if($atype == 1){
                    $giftname = $this->gwlrsigngifts[$giftid];
                }
                elseif ($atype == 2){
                    $giftname = $this->ylztsigngifts[$giftid];
                }
                elseif ($atype == 3){
                    $giftname = $this->xjhxsigngifts[$giftid];
                }
                $this->echojson(1, array(
                    'giftname' => $giftname,
                    'cdk' => $nygift['cdk'],
                    'signedday' => $this->getsignedday($atype)
                ));
            } else {
                $this->echojson(0, '领取失败');
            }
        } else {
            $this->echojson(0, 'CDK发放完毕');
        }
    }


    //检查点击天数是否签到
    private function checkdayissign($giftid, $atype)
    {
        $openid = $_COOKIE['openid'];
		$className = 'ActionNewyearGift'.$atype.'Model';
        $gift = new $className();
        //$gift = new ActionNewyearGiftModel();
        return $gift->count(array(
            'openid' => $openid,
            'giftid' => $giftid,
            'status' => 2,
            'atype' => $atype
        ));
    }

    //获取已签到天数
    private function getsignedday($atype)
    {
        $openid = $_COOKIE['openid'];
		$className = 'ActionNewyearGift'.$atype.'Model';
        $gift = new $className();
        //$gift = new ActionNewyearGiftModel();
        return $gift->count(array(
            'openid' => $openid,
            'status' => 2,
            'giftid' => array('not in', array(9, 100, 101, 102)),
            'atype' => $atype
        ));
    }


    //获取code
    public function getUserCode()
    {
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->AppID . '&redirect_uri=' . urlencode($this->callback) . '&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
        header("Location:$url");
    }

    //接收微信请求
    public function getopenid($code)
    {
        if (!empty($code)) {
            $openid = $_COOKIE['openid'];
            if ($openid) {
                return $openid;
            } else {
                $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->AppID . '&secret=' . $this->AppSecret . '&code=' . $code . '&grant_type=authorization_code';
                $res = json_decode($this->http_get($url), true);
                if (isset($res['openid'])) {
                    setcookie('code', $code, time() + 3600);
                    setcookie('openid', $res['openid'], time() + 3600);
                    return $res['openid'];
                }
            }
        }
        return false;
    }

    /**
     * 获取中奖礼品CDK
     */
    private function getwingiftcdk($atype)
    {
		$className = 'ActionNewyearGift'.$atype.'Model';
        $giftmodel = new $className();
        //$giftmodel = new ActionNewyearGiftModel();
        $giftret = $giftmodel->selectRow('id,giftid,cdk', array(
            'giftid' => array('in',array(100,101,102)),
            'status' => 1,
            'atype' => $atype
        ), 'rand()');
        if ($giftret) {
            return $giftret;
        } else {
            return array();
        }
    }

    //判断是签到第几天
    private function checksignday()
    {
        if (date("Ymd") >= '20170127') {
            $datetime1 = date_create('2017-01-27');
            $datetime2 = date_create(date("Y-m-d"));
            $interval = date_diff($datetime1, $datetime2);
            $sign_day = $interval->days + 1;
        } else {
            $sign_day = 0;
        }
        return $sign_day;
    }


    private function http_get($url)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

    //输出json
    private function echojson($rs, $msg, $code = null)
    {
        $data = array(
            'rs' => $rs,
            'msg' => $msg
        );
        if ($code) {
            $data['code'] = $code;
        }
        echo json_encode($data);
        exit();
    }


    private function array_merge_ny($arr1 = array(), $arr2 = array())
    {
        $data = array();
        if ($arr1) {
            foreach ($arr1 as $k => $v) {
                $data[$k] = $v;
            }
        }
        if ($arr2) {
            foreach ($arr2 as $k => $v) {
                $data[$k] = $v;
            }
        }
        return $data;
    }

    //redis缓存token，jsapi_tiket等
    public function generate(){

        $redis = $this->contentRedis();
        if($redis){
            $Prefix = "getToken|".$GLOBALS['domain']."|".$_SERVER['REQUEST_URI'];
            if(!$redis->exists($Prefix) || ($redis->exists($Prefix) && $redis->get($Prefix)=='')){
                //获取服务器数据
                $data = $this->getToken();
                $redis->set($Prefix,json_encode($data));
                $redis->expire($Prefix,7200);
            }else{
                $data = json_decode($redis->get($Prefix),true);
            }
        }else{
            $data = $this->$this->generate();
        }
        return $data;
    }

    //连接redis
    function contentRedis(){

        $redis = new Redis();
        $redis->connect($GLOBALS['redis_host'], $GLOBALS['redis_port']);
        return $redis;
    }


    public function getToken(){

        //获取token
        $result = $this->http_get('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->AppID.'&secret='.$this->AppSecret);
        $json = json_decode($result,true);
        if(isset($json['access_token'])){
            $this->access_token = $json['access_token'];
            //获取ticket
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$json['access_token'];
            $res = json_decode ( $this->http_get ( $url ) );
            $this->ticket = $res->ticket;
        }
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $signature = $this->getRandChar(16);
        return  $this->getSignPackage($this->ticket,$url,time(),$signature);
    }

    //随机字符串
    function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }


    //返回配置串
    private function getSignPackage($jsapiTicket,$url,$timestamp,$nonceStr) {

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1 ( $string );

        $signPackage["appId"] = $this->AppID;
        $signPackage["nonceStr"] = $nonceStr;
        $signPackage["timestamp"] = $timestamp;
        $signPackage["signature"] = $signature;
        $signPackage["rawString"] = $string;
        $signPackage["url"] = $url.'&is_share=true';

        return $signPackage;
    }
}