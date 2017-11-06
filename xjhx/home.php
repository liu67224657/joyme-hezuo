<?php

/**
 * ----------------------------------------------------------------
 * 微信登录-请求页面
 * ----------------------------------------------------------------
 * [访问URL]
 * http://mysite.com/partner_login/index.php
 * ----------------------------------------------------------------
 * [功能描述]
 * 用于前端页面请求调用微信登录界面的入口
 * ----------------------------------------------------------------
 * [参数说明]
 * redirect_url  string  跳转回网站的地址，默认：'http://m.baidu.com/'
 * ----------------------------------------------------------------
 * @author Corwien
 * @version 2016-05
 * ----------------------------------------------------------------
 */


// 微信服务号  
$appid = 'wx7a3a9bdf85d16731';

// 获取参数，前端传过来需要跳转回的链接
$return_url = $_GET['return_url'];
$return_url = !empty($return_url) ? $return_url : 'http://m.baidu.com/';

// 添加随机值
$rand_num = rand(1,10000);

// 回调页面
$call_url = 'http://temp.moleading.cn/callback.php';

// 链接
$redirect_uri = urlencode($call_url.'?goback_url='.$return_url);

$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&no='.$rand_num.'&scope=snsapi_userinfo&state=STATE&connect_redirect=1#wechat_redirect';

// 可以在此添加日志或缓存记录，查看这个页面是否请求了两次

// 请求微信
header("Location: ".$url);

?>