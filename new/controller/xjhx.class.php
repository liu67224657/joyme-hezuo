<?php

/**
 * Description: 星际火线相关活动
 * Author: gradydong
 * Date: 2017/2/14
 * Time: 10:38
 * Copyright: Joyme.com
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'wxapp.class.php');

use Joyme\core\Request;
use Joyme\page\Page;

class xjhx extends wxapp
{

    //每页显示条数
    public $pb_show_num = 10;

    //每日限制次数
    public $votes_umber = 5;


    public $isFromShare = false;

    public $data = array();

    function __construct()
    {
        //线上账号
        $this->AppID = 'wx28d67b05018d0f19';
        $this->AppSecret = 'dad223d60f6d9d6ee3ca7d4ce2326f5a';

        $this->data['shareJsPath'] = 'http://hezuo.joyme.com/new/static/script/jweixin-1.1.0.js';


        parent::__construct();
    }


    function init($method)
    {
        if ($GLOBALS['domain'] == 'alpha') {
            $openid = Request::getParam('openid');
            setcookie('openid', $openid, time() + 3600);
        } else {
            if(empty($_COOKIE['openid'])){
                $code = Request::getParam('code');
                if (empty($code)) {
                    $this->callback = 'http://' . $_SERVER['HTTP_HOST'] . '/new/?c=xjhx&a='.$method;
                    $this->getUserCode();
                }
                $this->getopenid($code);
            }
//            $this->share_url = 'http://hezuo.joyme.com/new/?c=xjhx&a='.$method;
//            $this->data['conf'] = $this->generate();
            $this->data['conf'] = $this->getaXJHXSignPackage();
        }
    }


    //首页展示
    function index()
    {
        $this->init('index');
        render($this->data, 'web', 'xjhx/index');
    }


    //参赛必看
    function mustSee()
    {
        $this->init('mustSee');
        render($this->data, 'web', 'xjhx/mustsee');
    }

    //丰厚奖励
    function reward()
    {
        $this->init('mustSee');
        render($this->data, 'web', 'xjhx/reward');
    }


    //报名页面
    function signUp()
    {
        if ($_POST) {
            $openid = $_COOKIE['openid'];
            if(empty($openid)){
                $this->echojson(0, 'openid不能为空,请重新进入页面');
            }
            //头像
            $avatar = Request::getParam('avatar');
            if(empty($avatar)){
                $this->echojson(0, '头像不能为空');
            }else{
                $data['avatar'] = $avatar;
            }
            //军团名称
            $corps_name = Request::getParam('corps_name');
            if(empty($corps_name)){
                $this->echojson(0, '军团名称不能为空');
            }else{
                $data['corps_name'] = $corps_name;
            }
            //团长名称
            $colonel_name = Request::getParam('colonel_name');
            if(empty($colonel_name)){
                $this->echojson(0, '团长名称不能为空');
            }else{
                $data['colonel_name'] = $colonel_name;
            }
            //系统
            $system_type = Request::getParam('system_type');
            if(empty($system_type)){
                $this->echojson(0, '系统不能为空');
            }else{
                $data['system_type'] = $system_type;
            }
            //大区
            $area = Request::getParam('area');
            if(empty($area)){
                $this->echojson(0, '大区不能为空');
            }else{
                $data['area'] = $area;
            }
            //宣言
            $decla = Request::getParam('decla');
            if(empty($decla)){
                $this->echojson(0, '宣言不能为空');
            }else{
                $data['decla'] = $decla;
            }
            //联系方式
            $contacts = Request::getParam('contacts');
            if(empty($contacts)){
                $this->echojson(0, '联系方式不能为空');
            }else{
                $data['contacts'] = $contacts;
            }
            $data['create_time'] = time();
            $data['openid'] = $openid;
            $xjhxjtzbcorps = new XjhxJtzbCorpsModel();
            $xjhxjtzbcorpscount = $xjhxjtzbcorps->count(array(
               'openid' => $openid
            ));
            if(empty($xjhxjtzbcorpscount)){
                $result = $xjhxjtzbcorps->insert($data);
                if ($result) {
                    $this->echojson(1, '报名成功');
                } else {
                    $this->echojson(0, '报名失败');
                }
            }else{
                $this->echojson(0, '抱歉，您已经报名了');
            }
        } else {
            $this->init('signUp');
            render($this->data, 'web', 'xjhx/signup');
        }
    }


    //军团风采
    function jtzbList()
    {
        $this->init('jtzbList');
        $openid = $_COOKIE['openid'];
        $sort = intval(Request::getParam('sort',0));
        if($sort == 1){
            $order = ' create_time DESC';
        }elseif ($sort == 2){
            $order = ' vote_num DESC';
        }else{
            $sort = 1;
            $order = ' create_time DESC';
        }
        $where= array('status' => 1);
        $sy = (int)Request::getParam('sy');
        $keyword = Request::getParam('keyword');
        if(!empty($sy)){
            $where['system_type'] = $sy;
        }else{
            if(empty($keyword)){
                $sy = 2;
                $where['system_type'] = $sy;
            }
        }

        if(!empty($keyword)){
            if(is_numeric($keyword)){
                $where['id'] = (int)$keyword;
            }else{
                $where['corps_name'] = $keyword;
            }
        }
        $pb_page = (int)Request::getParam('pb_page',1); //获取当前页码
        $limit = 8;
        $skip = intval(($pb_page-1)*$limit);

        $xjhxjtzbcorps = new XjhxJtzbCorpsModel();
        $xjhxjtzbcorpslists = $xjhxjtzbcorps->select('id,corps_name,colonel_name,system_type,decla,avatar,vote_num,area',$where,$order,$limit,$skip);
        $xjhxjtzbvote = new XjhxJtzbVoteModel();
        $xjhxjtzbvotelists = $xjhxjtzbvote->select('corps_id',array(
            'openid' => $openid,
            'time' => date('Y-m-d')
        ));
        $corps_ids = [];
        if($xjhxjtzbvotelists){
            $corps_ids = array_column($xjhxjtzbvotelists,'corps_id');
        }
        if($xjhxjtzbcorpslists){
            foreach ($xjhxjtzbcorpslists as $k => $v){
                if(in_array($v['id'],$corps_ids)){
                    $xjhxjtzbcorpslists[$k]['is_like'] = 1;
                }else{
                    $xjhxjtzbcorpslists[$k]['is_like'] = 0;
                }
            }
        }
        if(count($xjhxjtzbcorpslists)==1){
            $sy = $xjhxjtzbcorpslists[0]['system_type'];
        }
        $this->data['corps'] = $xjhxjtzbcorpslists;
        $this->data['sort'] = $sort;
        $this->data['sy'] = $sy;
        $this->data['keyword'] = $keyword;
        $this->data['pb_page'] = $pb_page;

        if ($_POST) {
            if($xjhxjtzbcorpslists){
                $this->echojson(1, $xjhxjtzbcorpslists);
            }else{
                $this->echojson(0, array());
            }
        }else{
            render($this->data, 'web', 'xjhx/jtzblist');
        }
    }


    //投票
    function vote()
    {
        if (date("Ymd") >= '20170308') {
            $this->echojson(0, '活动已结束');
        }
        $openid = $_COOKIE['openid'];
        if (empty($openid)) {
            $this->echojson(0, 'openid不能为空，请重新进入页面');
        }
        $data['openid'] = $openid;
        $id = intval(Request::getParam('id'));
        if (empty($id)) {
            $this->echojson(0, 'id不能为空');
        }
        $data['corps_id'] = $id;
        $data['time'] = date('Y-m-d');
        $xjhxjtzbvote = new XjhxJtzbVoteModel();
        //今日投票次数
        $todayvotenum = $xjhxjtzbvote->count(array(
            'openid'=>$openid,
            'time' => date('Y-m-d')
        ));
        if($todayvotenum < 5){
            $result = $xjhxjtzbvote->insert($data);
            if ($result) {
                $xjhxjtzbcorps = new XjhxJtzbCorpsModel();
                if($xjhxjtzbcorps->updateCorpsVoteNum($id)){
                    $this->echojson(1, '投票成功');
                }else{
                    $this->echojson(0, '投票失败');
                }
            } else {
                $this->echojson(0, '投票失败');
            }
        }else{
            $this->echojson(2, '今日已达到投票上限，请明日再来');
        }
    }

    //审核列表
    public function adminList()
    {
        $xjhxjtzbcorps = new XjhxJtzbCorpsModel();
        $id = (int)Request::post('id');
        if ($id && is_numeric($id)) {
            $result = $xjhxjtzbcorps->update(array(
                'status' => 1
            ), array(
                'id' => $id
            ));
            if ($result) {
                $this->echojson(1, '修改成功');
            } else {
                $this->echojson(0, '修改失败');
            }
        } else {
            $pb_page = Request::get('pb_page', 1); //获取当前页码
            $total = $xjhxjtzbcorps->count();
            $limit = $this->pb_show_num;
            $offset = ($pb_page - 1) * $this->pb_show_num;
            $xjhxjtzbcorpslists = $xjhxjtzbcorps->select('*', array(), ' create_time DESC', $limit, $offset);
            if($xjhxjtzbcorpslists){
                foreach ($xjhxjtzbcorpslists as $k => $v){
                    if($v['system_type'] == 1){
                        $xjhxjtzbcorpslists[$k]['system_type'] = 'Android';
                    }elseif ($v['system_type'] == 2){
                        $xjhxjtzbcorpslists[$k]['system_type'] = 'ios';
                    }
                }
            }

            $this->data['item'] = $xjhxjtzbcorpslists;
            $_page = new Page(array('total' => $total, 'perpage' => $this->pb_show_num, 'nowindex' => $pb_page, 'pagebarnum' => 8));
            $this->data['page'] = $_page->show(2);

            render($this->data, 'web', 'xjhx/adminlist');
        }
    }


    //刷赞
    public function updatevote()
    {
        $id = (int)Request::getParam('id');
        $num = (int)Request::getParam('num');
        if ($id && $num && $num > 0) {
            $xjhxjtzbcorps = new XjhxJtzbCorpsModel();
            $result = $xjhxjtzbcorps->updateCorpsVoteByAdmin($id,$num);
            if ($result) {
                echo 'ok';
            } else {
                echo 'error';
            }
        } else {
            echo '参数不正确';
        }
    }


    public function getaXJHXSignPackage() {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $this->AppID,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        $redis = $this->contentRedis();
        if ($redis) {
            $Prefix = "getTicket|".$GLOBALS['domain']."|".$this->share_url;
            if (!$redis->exists($Prefix) || ($redis->exists($Prefix) && $redis->get($Prefix) == '')) {
                $accessToken = $this->getAccessToken();
                // 如果是企业号用以下 URL 获取 ticket
                $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
                $res = json_decode($this->http_get($url));
                $ticket = $res->ticket;
                if ($ticket) {
                    $redis->set($Prefix, $ticket);
                    $redis->expire($Prefix, 7200);
                    return $ticket;
                }else{
                    return $ticket;
                }
            } else {
                return $redis->get($Prefix);
            }
        } else {
            return false;
        }
    }

    private function getAccessToken() {
        $redis = $this->contentRedis();
        if ($redis) {
            $Prefix = "getToken|".$GLOBALS['domain']."|".$this->share_url;
            if (!$redis->exists($Prefix) || ($redis->exists($Prefix) && $redis->get($Prefix) == '')) {
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->AppID&secret=$this->AppSecret";
                $res = json_decode($this->http_get($url));
                $access_token = $res->access_token;
                if ($access_token) {
                    $redis->set($Prefix, $access_token);
                    $redis->expire($Prefix, 7200);
                    return $access_token;
                }else{
                    return $access_token;
                }
            } else {
                return $redis->get($Prefix);
            }
        } else {
            return false;
        }
    }


}