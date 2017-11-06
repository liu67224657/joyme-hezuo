<?php

/**
 * Description: 微信开发共用
 * Author: gradydong
 * Date: 2017/2/8
 * Time: 16:48
 * Copyright: Joyme.com
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'app.class.php');

use Joyme\core\Request;
use Joyme\core\Log;

class wxapp extends app
{
    //***微信认证
    //AppID
    protected $AppID = '';
    //AppSecret
    protected $AppSecret = '';
    //access_token
    protected $access_token = '';
    //openid
    protected $openid = '';
    //回掉地址
    protected $callback = '';

    protected $shareJsPath = '';

    //密钥
    protected $sign = '~(@jm@qyz!';

    //ticket
    protected $ticket = '';
    protected $share_url = '';


    function __construct()
    {
        global $GLOBALS;
        $this->shareJsPath = 'http://' . $_SERVER['HTTP_HOST'] . '/new/static/script/jweixin-1.1.0.js';

        parent::__construct();
    }

    //连接redis
    protected function contentRedis()
    {
        $redis = new Redis();
        $redis->connect($GLOBALS['redis_host'], $GLOBALS['redis_port']);
        return $redis;
    }

    //获取code
    protected function getUserCode()
    {
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->AppID . '&redirect_uri=' . urlencode($this->callback) . '&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
        header("Location:$url");
    }

    //接收微信请求
    protected function getopenid($code)
    {
        if (!empty($code)) {
            $openid = $_COOKIE['openid'];
            if ($openid) {
                return $openid;
            } else {
                $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->AppID . '&secret=' . $this->AppSecret . '&code=' . $code . '&grant_type=authorization_code';
                $res = json_decode($this->http_get($url), true);
                if (isset($res['openid'])) {
                    setcookie('code', $code, time() + 86400);
                    setcookie('openid', $res['openid'], time() + 86400);
                    return $res['openid'];
                }
            }
        }
        return false;
    }


    /**
     * redis缓存token，jsapi_tiket等
     * $aname 活动名称，注意别重复了，英文小写添加下划线
     */
    protected function generate($aname=null)
    {

        $redis = $this->contentRedis();
        if ($redis) {
            if($this->share_url){
                $Prefix = "getToken|".$GLOBALS['domain']."|".$this->share_url;
            }else{
                $Prefix = "getToken|".$GLOBALS['domain']."|".$_SERVER['REQUEST_URI'];
            }
            if (!$redis->exists($Prefix) || ($redis->exists($Prefix) && $redis->get($Prefix) == '')) {
                //获取服务器数据
                $data = $this->getToken();
                $redis->set($Prefix, json_encode($data));
                $redis->expire($Prefix, 7200);
            } else {
                $data = json_decode($redis->get($Prefix), true);
            }
        } else {
            $data = array();
        }
        return $data;
    }



    protected function getToken()
    {
        //获取token
        $result = $this->http_get('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->AppID . '&secret=' . $this->AppSecret);
        $json = json_decode($result, true);
        if (isset($json['access_token'])) {
            $this->access_token = $json['access_token'];
            //获取ticket
            $redis = $this->contentRedis();
            $Prefix = "getTicket|".$GLOBALS['domain']."|".$_SERVER['REQUEST_URI'];
            if (!$redis->exists($Prefix) || ($redis->exists($Prefix) && $redis->get($Prefix) == '')) {
                //获取服务器数据
                $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=" . $json['access_token'];
                $res = json_decode($this->http_get($url));
                $this->ticket = $res->ticket;
                $redis->set($Prefix, $res->ticket);
                $redis->expire($Prefix, 7200);
            } else {
                $this->ticket = $redis->get($Prefix);
            }
        }
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $signature = $this->getRandChar(16);
        return $this->getSignPackage($this->ticket, $url, time(), $signature);
    }

    //随机字符串
    protected function getRandChar($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }


    //返回配置串
    protected function getSignPackage($jsapiTicket, $url, $timestamp, $nonceStr)
    {

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        Log::debug($string);
        $signPackage["jsapi_ticket"] = $jsapiTicket;
        $signPackage["appId"] = $this->AppID;
        $signPackage["nonceStr"] = $nonceStr;
        $signPackage["timestamp"] = $timestamp;
        $signPackage["signature"] = $signature;
        $signPackage["rawString"] = $string;
        $signPackage["url"] = $url . '&is_share=true';

        return $signPackage;
    }


    protected function http_get($url)
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
    protected function echojson($rs, $msg, $code = null)
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

}