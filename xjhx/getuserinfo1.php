<?php
require_once("config.php");
$appid = APP_ID;
$appsecret = APP_SECRET;
$svr_host = SERVER_HOST;
$code = $_GET["code"];
$state = $_GET["state"];

if($state='101')
{
//第一步:取得openid
$oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
$oauth2 = getJson($oauth2Url);
 

//echo '<pre>';
//print_r($oauth2);
//echo '</pre>';

//第二步:根据全局access_token和openid查询用户信息
$access_token = "";
if(isset($oauth2["access_token"]))
{
    $access_token = $oauth2["access_token"];  
}

$openid = "";
if(isset($oauth2["openid"]))
{
    $openid = $oauth2['openid'];  
}
//$get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
$get_user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
$userinfo = getJson($get_user_info_url);
 
//打印用户信息
//echo '<pre>';
//print_r($userinfo);
//echo '</pre>';
}	
else
{
	echo 'err code';
	echo $state;
}

$imgurl = "http://hezuo.joyme.com/xjhx/resource/my_images/headbg.png";
if(isset($userinfo['headimgurl']))
{
    $imgurl = $userinfo['headimgurl'];  
}

$nickname = "";
if(isset($userinfo['nickname']))
{
    $nickname = $userinfo['nickname'];
}
 



function getJson($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output, true);
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>星际火线</title>
    <meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="full-screen" content="true"/>
    <meta name="screen-orientation" content="portrait"/>
    <meta name="x5-fullscreen" content="true"/>
    <meta name="360-fullscreen" content="true"/>
    <style>
        html, body {
            -ms-touch-action: none;
            background: #75A0E0;
            padding: 0;
            border: 0;
            margin: 0;
            height: 100%;
        }
    </style>

    <!--这个标签为通过egret提供的第三方库的方式生成的 javascript 文件。删除 modules_files 标签后，库文件加载列表将不会变化，请谨慎操作！-->
    <!--modules_files_start-->
    <script egret="lib" src="libs/modules/egret/egret.min.js"></script>
    <script egret="lib" src="libs/modules/egret/egret.web.min.js"></script>
    <script egret="lib" src="libs/modules/res/res.min.js"></script>
    <script egret="lib" src="libs/modules/eui/eui.min.js"></script>
    <script egret="lib" src="libs/modules/tween/tween.min.js"></script>
    <!--modules_files_end-->

    <!--这个标签为不通过egret提供的第三方库的方式使用的 javascript 文件，请将这些文件放在libs下，但不要放在modules下面。-->
    <!--other_libs_files_start-->
    <!--other_libs_files_end-->

    <!--这个标签会被替换为项目中所有的 javascript 文件。删除 game_files 标签后，项目文件加载列表将不会变化，请谨慎操作！-->
    <!--game_files_start-->
    <script src="main.min.js"></script>
    <!--game_files_end-->
</head>
<body>

    <div style="margin: auto;width: 100%;height: 100%;" class="egret-player"
         id="gameDiv"
         data-entry-class="Main"
         data-orientation="portrait"
         data-scale-mode="noBorder"
         data-frame-rate="30"
         data-content-width="640"
         data-content-height="1136"
         data-show-paint-rect="false"
         data-multi-fingered="2"
         data-show-fps="false" data-show-log="false"
         data-show-fps-style="x:0,y:0,size:12,textColor:0xffffff,bgAlpha:0.9">
    </div>
    <script>
        /**
         * {
         * "renderMode":, //引擎渲染模式，"canvas" 或者 "webgl"
         * "audioType": "" //使用的音频类型，0:默认，1:qq audio，2:web audio，3:audio
         * "antialias": //WebGL模式下是否开启抗锯齿，true:开启，false:关闭，默认为false
         * }
         **/
        egret.runEgret({renderMode:"webgl", audioType:0});
    </script>
    <p id="yournick" hidden="hidden"><?php echo $nickname; ?></p>
    <p id="yourpic" hidden="hidden"><?php echo $imgurl; ?></p>
</body>
</html>