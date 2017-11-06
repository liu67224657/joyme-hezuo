<?php
require_once("config.php");
$appid = APP_ID;
$svr_host = SERVER_HOST;
$userinfo_url = "http://{$svr_host}/getuserinfo.php";
$redirect_uri=urlencode($userinfo_url);
$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state=101&connect_redirect=1#wechat_redirect";
header("Location:".$url);
?>