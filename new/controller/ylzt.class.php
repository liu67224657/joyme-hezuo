<?php

/**
 * Description:
 * Author: gradydong
 * Date: 2017/3/6
 * Time: 15:22
 * Copyright: Joyme.com
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'wxapp.class.php');

use Joyme\core\Request;

class ylzt extends wxapp
{

    public $data = array();


    function __construct()
    {
        //线上账号
        /*$this->AppID = 'wx28d67b05018d0f19';
        $this->AppSecret = 'dad223d60f6d9d6ee3ca7d4ce2326f5a';*/

        $this->AppID = 'wxe6eafdebe1a95bd5';
        $this->AppSecret = 'b43ed258cc8bb38843395507887e93d9';

        $this->data['shareJsPath'] = 'http://hezuo.joyme.com/new/static/script/jweixin-1.1.0.js';
        $this->data['conf'] = $this->getaYLZTSignPackage();
        parent::__construct();
    }

    public function cxblyhbm()
    {
        $usermodel = new ActiveUsersModel();
        $rname = Request::getParam('rname');
        $sopenid = Request::getParam('sopenid');
        if ($rname && $sopenid) {
            $this->data['is_display'] = 'display: block;';
            $this->data['rname'] = $rname;
            $this->callback = 'http://hezuo.joyme.com/new/?c=ylzt&a=cxblyhbm&rname=' . $rname . '&sopenid=' . $sopenid;
        } else {
            $this->data['is_display'] = '';
            $this->data['rname'] = '';
            $this->callback = 'http://hezuo.joyme.com/new/?c=ylzt&a=cxblyhbm';
        }
        $openid = $_COOKIE['openid'];
        if (empty($openid)) {
            $code = Request::getParam('code', '');
            file_put_contents("/opt/servicelogs/phplog/test.log", var_export(array('code' => $code), true), FILE_APPEND);
            if (empty($code)) {
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->AppID . "&redirect_uri=" . urlencode($this->callback) . "&response_type=code&scope=snsapi_userinfo&state=101&connect_redirect=1#wechat_redirect";
                header("Location:" . $url);
            } else {
                $userauth = $this->getuseraccesstoken($code);
                file_put_contents("/opt/servicelogs/phplog/test.log", var_export(array('userauth' => $userauth), true), FILE_APPEND);
                if ($userauth) {
                    $this->data['openid'] = $userauth['openid'];
                    setcookie('openid', $userauth['openid'], time() + 86400);
                    $ruser = $usermodel->selectRow('openid,nickname,headimgurl', array(
                        'openid' => $userauth['openid']
                    ));
                    if (empty($ruser)) {
                        $userinfo = $this->getUserinfo($userauth['access_token'], $userauth['openid']);
                        file_put_contents("/opt/servicelogs/phplog/test.log", var_export(array('userinfo' => $userinfo), true), FILE_APPEND);
                        if ($userinfo) {
                            $userdata = array(
                                'openid' => $userauth['openid'],
                                'nickname' => $userinfo['nickname'],
                                'headimgurl' => $userinfo['headimgurl'],
                                'create_time' => time()
                            );
                            $dumpdata = array(
                                $_REQUEST,
                                $userauth,
                                $userinfo,
                                $userdata
                            );
                            file_put_contents("/opt/servicelogs/phplog/test.log", var_export($dumpdata, true), FILE_APPEND);
                            $usermodel->insert($userdata);
                            $this->data['userinfo'] = $userinfo;
                        }
                    } else {
                        $this->data['userinfo'] = $ruser;
                    }
                }
            }
        } else {
            $ruser = $usermodel->selectRow('openid,nickname,headimgurl', array(
                'openid' => $openid
            ));
            $this->data['userinfo'] = $ruser;
            $this->data['openid'] = $openid;
        }


        //获取发送者的头像和昵称
        if ($sopenid) {
            $senduser = $usermodel->selectRow('openid,nickname,headimgurl', array(
                'openid' => $sopenid
            ));
            if ($senduser) {
                $this->data['senduser'] = $senduser;
            }
        }

        render($this->data, 'web', 'ylzt/cxblyhbm');
    }


    public function getCdk()
    {
        $openid = $_COOKIE['openid'];
        if (!empty($openid)) {
            $cdkmodel = new ActiveCdkModel();
            $cdkcount = $cdkmodel->count(array(
                'openid' => $openid,
                'status' => 1,
                'atype' => 1
            ));
            if (empty($cdkcount)) {
                $cdklist = $cdkmodel->selectRow('id,cdk', array(
                    'status' => 0,
                    'atype' => 1
                ));
                if ($cdklist) {
                    $result = $cdkmodel->update(array(
                        'openid' => $openid,
                        'status' => 1,
                        'gettime' => time()
                    ), array(
                        'id' => $cdklist['id']
                    ));
                    if ($result) {
                        $this->echojson(1, array(
                            'cdk' => $cdklist['cdk']
                        ));
                    } else {
                        $this->echojson(0, '领取失败');
                    }
                } else {
                    $this->echojson(2, '对不起，奖励已发放完毕');
                }
            } else {
                $this->echojson(3, '对不起，你已领取过福利');
            }
        } else {
            $this->echojson(0, '参数错误');
        }
    }

    protected function getUserinfo($access_token, $openid)
    {
        $get_user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
        return json_decode($this->http_get($get_user_info_url), true);
    }

    protected function getuseraccesstoken($code)
    {
        $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->AppID . "&secret=" . $this->AppSecret . "&code=" . $code . "&grant_type=authorization_code";
        return json_decode($this->http_get($oauth2Url), true);
    }


    protected function getaYLZTSignPackage()
    {
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
            "appId" => $this->AppID,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket()
    {
        $redis = $this->contentRedis();
        if ($redis) {
            $Prefix = "getTicket|com|" . $this->AppID;
            if (!$redis->exists($Prefix) || ($redis->exists($Prefix) && $redis->get($Prefix) == '')) {
                $accessToken = $this->getAccessToken();
                $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
                $res = json_decode($this->http_get($url));
                $ticket = $res->ticket;
                if ($ticket) {
                    $redis->set($Prefix, $ticket);
                    $redis->expire($Prefix, 7200);
                    return $ticket;
                } else {
                    return $ticket;
                }
            } else {
                return $redis->get($Prefix);
            }
        } else {
            return false;
        }
    }

    private function getAccessToken()
    {
        $redis = $this->contentRedis();
        if ($redis) {
            $Prefix = "getToken|com|" . $this->AppID;
            if (!$redis->exists($Prefix) || ($redis->exists($Prefix) && $redis->get($Prefix) == '')) {
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->AppID&secret=$this->AppSecret";
                $res = json_decode($this->http_get($url));
                $access_token = $res->access_token;
                if ($access_token) {
                    $redis->set($Prefix, $access_token);
                    $redis->expire($Prefix, 7200);
                    return $access_token;
                } else {
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