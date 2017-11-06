<?php

/**
 * Description:
 * Author: gradydong
 * Date: 2017/2/27
 * Time: 10:57
 * Copyright: Joyme.com
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'wxapp.class.php');

use Joyme\core\Request;

class qmqjmu extends wxapp
{

    public $data = array();


    function __construct()
    {
        //线上账号
        $this->AppID = 'wx28d67b05018d0f19';
        $this->AppSecret = 'dad223d60f6d9d6ee3ca7d4ce2326f5a';

        $this->data['shareJsPath'] = 'http://hezuo.joyme.com/new/static/script/jweixin-1.1.0.js';
//        $this->share_url = 'http://hezuo.joyme.com/new/?c=qmqjmu&a=index';
//        $this->data['conf'] = $this->generate();
        $this->data['conf'] = $this->getaQMQJMUSignPackage();
        parent::__construct();
    }

    public function index()
    {
        $qmqjmuusermodel = new ActionQmqjmuUsersModel();
        $rname = Request::getParam('rname');
        $sopenid = Request::getParam('sopenid');
        if($rname&&$sopenid){
            $this->data['is_display'] = 'display: block;z-index: 999;';
            $this->data['rname'] = $rname;
            $this->callback = 'http://' . $_SERVER['HTTP_HOST'] . '/new/?c=qmqjmu&a=index&rname='.$rname.'&sopenid='.$sopenid;
        }else{
            $this->data['is_display'] = '';
            $this->data['rname'] = '';
            $this->callback = 'http://' . $_SERVER['HTTP_HOST'] . '/new/?c=qmqjmu&a=index';
        }
        $code = Request::getParam('code');
        if (empty($code)) {
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->AppID . "&redirect_uri=" . urlencode($this->callback) . "&response_type=code&scope=snsapi_userinfo&state=101&connect_redirect=1#wechat_redirect";
            header("Location:" . $url);
        }

        $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->AppID . "&secret=" . $this->AppSecret . "&code=" . $code . "&grant_type=authorization_code";
        $res = json_decode($this->http_get($oauth2Url), true);
        if($res){
            $this->data['openid'] = $res['openid'];
            $qmqjmuuser = $qmqjmuusermodel->selectRow('openid,nickname,headimgurl',array(
                'openid' => $res['openid']
            ));
            if(empty($qmqjmuuser)){
                $get_user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $res['access_token'] . "&openid=" . $res['openid'] . "&lang=zh_CN";
                $userinfo = json_decode($this->http_get($get_user_info_url), true);
                if ($userinfo) {
                    $userdata = array();
                    if($res['openid']){
                        $userdata['openid'] = $res['openid'];
                    }
                    if($userinfo['nickname']){
                        $userdata['nickname'] = $userinfo['nickname'];
                    }
                    if($userinfo['openid']){
                        $userdata['headimgurl'] = $userinfo['headimgurl'];
                    }
                    if($userdata){
                        $userdata['create_time'] = time();
                        $qmqjmuusermodel->insert($userdata);
                    }

                    $this->data['userinfo'] = $userinfo;
                }
            }else{
                $this->data['userinfo'] = $qmqjmuuser;
            }
        }

        //获取发送者的头像和昵称
        if($sopenid){
            $sqmqjmuuser = $qmqjmuusermodel->selectRow('openid,nickname,headimgurl',array(
                'openid' => $sopenid
            ));
            if($sqmqjmuuser){
                $this->data['senduser'] = $sqmqjmuuser;
            }
        }
        
        render($this->data, 'web', 'qmqjmu/index');
    }



    public function getaQMQJMUSignPackage() {
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
            $Prefix = "getTicket|com|".$this->AppID;
            if (!$redis->exists($Prefix) || ($redis->exists($Prefix) && $redis->get($Prefix) == '')) {
                $accessToken = $this->getAccessToken();
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
            $Prefix = "getToken|com|".$this->AppID;
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