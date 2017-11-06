<?php

/**
 * Description:权利荣耀活动
 * Author: gradydong
 * Date: 2017/2/8
 * Time: 16:48
 * Copyright: Joyme.com
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'wxapp.class.php');

use Joyme\core\Request;

class qlry extends wxapp
{
    //每日限制次数
    public $votes_umber = 3;


    public $users = array(
        '1' => '白桑',
        '2' => '西萌',
        '3' => '嘉儿',
        '4' => '小饕餮'
    );

    function __construct()
    {
        //线上账号
//        $this->AppID = 'wxd9f4682a859a0095';
//        $this->AppSecret = 'b6983a65c12884a5920a2e23a448496c';

        $this->AppID = 'wx28d67b05018d0f19';
        $this->AppSecret = 'dad223d60f6d9d6ee3ca7d4ce2326f5a';

        parent::__construct();
    }

    function index()
    {
        if ($GLOBALS['domain'] == 'alpha') {
            $openid = Request::getParam('openid');
            setcookie('openid', $openid, time() + 3600);
        } else {
            $code = Request::getParam('code');
            if (empty($code)) {
                $code = $_COOKIE['code'];
                if (empty($code)) {
                    $this->callback = 'http://' . $_SERVER['HTTP_HOST'] . '/new/?c=qlry&a=index';
                    $this->getUserCode();
                }
            }
            $this->getopenid($code);
        }

        $data['shareJsPath'] = $this->shareJsPath;
        $data['conf'] = $this->generate('qlry_vote_lottery');

        $usermodel = new QlryUsersModel();
        $userlists = $usermodel->select('user_id,vote_num',array(),'vote_num desc');
        if ($userlists) {
            foreach ($userlists as $k => $v){
                $userlists[$k]['user_name'] = $this->users[$v['user_id']];
            }
            $data['voteusers'] = $userlists;
        } else {
            $data['voteusers'] = array();
        }
        render($data, 'web', 'qlry/index');
    }

    //投票
    public function vote()
    {
        $qlryvotemodel = new QlryVoteModel();
        $user_id = intval(Request::getParam('user_id'));
        $openid = $_COOKIE['openid'];
        if (empty($user_id) || empty($openid)) {
            $this->echojson(0, '参数不正确');
        }
        //记录数据
        $data['openid'] = $openid;
        $data['time'] = date('Y-m-d');
        //查看是否可以投票
        if ($qlryvotemodel->getCountNumber($data) < $this->votes_umber) {
            $usermodel = new QlryUsersModel();
            $array['user_id'] = $user_id;
            $array['openid'] = $openid;
            $array['time'] = date('Y-m-d');
            //增加投票记录
            $result = $qlryvotemodel->addVoteInfo($array);
            if ($result) {
                //更新投票数
                if ($usermodel->updateUserVoteNum($user_id)) {
                    $usermodel = new QlryUsersModel();
                    $userlists = $usermodel->select('user_id,vote_num',array(),'vote_num desc');
                    foreach ($userlists as $k => $v){
                        $userlists[$k]['user_name'] = $this->users[$v['user_id']];
                    }
                    echo json_encode(array(
                        'rs' => 1,
                        'msg' => '投票成功',
                        'userlists' => $userlists
                    ));
                    exit();
                } else {
                    $this->echojson(0, '投票失败');
                }
            } else {
                $this->echojson(0, '投票失败');
            }
        } else {
            $this->echojson(0, '今日投票已达上限');
        }
    }

    //抽奖
    public function lottery()
    {
        $openid = $_COOKIE['openid'];
        if (empty($openid)) {
            $this->echojson(0, '参数不正确！');
        }
        //记录数据
        $qlryvotemodel = new QlryVoteModel();
        $uservotenum = $qlryvotemodel->getCountNumber(array(
            'openid' => $openid,
            'time' => date('Y-m-d')
        ));
        if($uservotenum>0 && $uservotenum<4){
            $qlrylrmodel = new QlryLotteryRecord();
            $qlrylrcount = $qlrylrmodel->count(array(
                'openid' => $openid,
                'rdate' => date('Y-m-d')
            ));
            if(empty($qlrylrcount)){
                $qlrylpmodel = new QlryLotteryProduct();
                $qlrylpwhere = array('pnum' => array('neq',0));
                //判断是否抽中过实物
                $qlrylrflag = $qlrylrmodel->count(array(
                    'openid' => $openid,
                    'pid' => array('in',array(4,5))
                ));
                if($qlrylrflag){
                    $qlrylpwhere['pid'] = 6;
                }
                $qlrylplists = $qlrylpmodel->select('pid,pnum',$qlrylpwhere);
                if($qlrylplists){
                    $lgrade = $this->getwinproduct($qlrylplists);
                    //测试代码，生产环境删除
                    if($openid=='test0001' || $openid=='test0002' ||$openid=='test0003'||$openid=='test0004'){
                        $lgrade = mt_rand(4,5);
                    }
                    $qlrylrresult = $qlrylrmodel->insert(array(
                        'openid' => $openid,
                        'pid' => $lgrade,
                        'rdate' => date('Y-m-d')
                    ));
                    if($qlrylrresult){
                        //更新奖品库存
                        $qlrylpmodel->updateProductNum($lgrade);
                        if($lgrade == 4){
                            $this->echojson(4, '恭喜勇士获得【优酷会员月卡】一张<br/>请发送关键字“不山不水”至《权力与荣耀》微信公众号领取
');
                        }
                        elseif ($lgrade == 5){
                            $this->echojson(5, '恭喜勇士获得【5元手机话费】一份<br/>请发送关键字“行而走之”至《权力与荣耀》微信公众号领取
');
                        }
                        elseif ($lgrade == 6){
                            $this->echojson(6, '恭喜勇士获得【游戏礼包】一份<br/>请发送关键字“我要礼包”至《权力与荣耀》微信公众号领取
');
                        }
                    }else{
                        $this->echojson(0, '系统错误！');
                    }
                }else{
                    $this->echojson(0, '奖品已发放完毕！');
                }
            }else{
                $this->echojson(0, '已参加抽奖，请明日再来！');
            }
        }
        elseif ($uservotenum==0){
            $this->echojson(0, '请先参加投票！');
        }
        else{
            $this->echojson(0, '参数不正确！');
        }
    }

    private function getwinproduct($qlrylplists){
        $pnums = array_column($qlrylplists,'pnum','pid');
        $lp_total = array_sum($pnums);
        $lnum = mt_rand(0,$lp_total);
        $winnum = 0;
        $lgrade = 0;
        foreach ($pnums as $k => $v){
            $winnum += $v;
            if($lnum<=$winnum){
                $lgrade = $k;
                break;
            }
        }
        return $lgrade;
    }


    //刷票
    public function updatevote()
    {
        $userid = Request::getParam('userid');
        $num = Request::getParam('num');
        if ($userid && $num && $num > 0) {
            $usermodel = new QlryUsersModel();
            $result = $usermodel->updateUserVoteByAdmin($userid, $num);
            if ($result) {
                echo 'ok';
            } else {
                echo 'error';
            }
        } else {
            echo '参数不正确';
        }
    }

}