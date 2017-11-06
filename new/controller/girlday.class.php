<?php

/**
 * Description:
 * Author: gradydong
 * Date: 2017/3/2
 * Time: 10:46
 * Copyright: Joyme.com
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'wxapp.class.php');

use Joyme\core\Request;
use Joyme\core\Log;

class girlday extends wxapp
{

    public $data = array();

    public $url = '';

    function __construct()
    {
        //线上账号
        $this->AppID = 'wx28d67b05018d0f19';
        $this->AppSecret = 'dad223d60f6d9d6ee3ca7d4ce2326f5a';

        $this->data['shareJsPath'] = 'http://hezuo.joyme.com/new/static/script/jweixin-1.1.0.js';

        $this->share_url = 'http://hezuo.joyme.com/new/?c=girlday&a=index';
//        $this->data['conf'] = $this->generate();
        $this->data['conf'] = $this->getaGDSignPackage();
        parent::__construct();
    }

    //首页展示
    function index()
    {
        if ($GLOBALS['domain'] == 'alpha') {
            $openid = Request::getParam('openid');
            setcookie('openid', $openid, time() + 3600);
        } else {
            if (empty($_COOKIE['openid'])) {
                $code = Request::getParam('code');
                if (empty($code)) {
                    $this->callback = 'http://' . $_SERVER['HTTP_HOST'] . '/new/?c=girlday&a=index';
                    $this->getUserCode();
                }
                $openid = $this->getopenid($code);
            } else {
                $openid = $_COOKIE['openid'];
            }
        }
        if ($openid) {
            $girlday = new ActiveGirldayUsersModel();
            $girldaycount = $girlday->count(array(
                'openid'=>$openid
            ));
            if(empty($girldaycount)){
                $girlday->insert(array(
                    'openid' => $openid,
                    'visit_num' => 1,
                    'create_time' => time()
                ));
            }else{
                $girlday->updateUsersVisitNum($openid);
            }
        }
        render($this->data, 'web', 'girlday/index');
    }

    //统计分析次数
    function sharenum()
    {
        $openid = $_COOKIE['openid'];
        if (empty($openid)) {
            $this->echojson(0, 'openid参数不能为空');
        }
        $girlday = new ActiveGirldayUsersModel();
        $result = $girlday->updateUsersShareNum($openid);
        if ($result) {
            $this->echojson(1, "Success");
        } else {
            $this->echojson(0, "Failure");
        }
    }

    //统计分析成功次数
    function sharednum()
    {
        $openid = $_COOKIE['openid'];
        if (empty($openid)) {
            $this->echojson(0, 'openid参数不能为空');
        }
        $girlday = new ActiveGirldayUsersModel();
        $result = $girlday->updateUsersSharedNum($openid);
        if ($result) {
            $this->echojson(1, "Success");
        } else {
            $this->echojson(0, "Failure");
        }
    }

    //获取分享配置
    function getconfig()
    {
        $openid = $_COOKIE['openid'];
        if (empty($openid)) {
            $this->echojson(0, 'openid参数不能为空');
        }
//        $data = $this->generate();
        $data = $this->getaGDSignPackage();
        if ($data) {
            $this->echojson(1, $data);
        } else {
            $this->echojson(0, "Failure");
        }
    }


    public function getaGDSignPackage() {
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
//            $Prefix = "getTicket|".$GLOBALS['domain']."|".$this->share_url;
            $Prefix = "getTicket|com|http://hezuo.joyme.com/new/?c=girlday&a=index";
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
//            $Prefix = "getToken|".$GLOBALS['domain']."|".$this->share_url;
            $Prefix = "getToken|com|http://hezuo.joyme.com/new/?c=girlday&a=index";
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