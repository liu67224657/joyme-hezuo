<?php
 /**
 * ----------------------------------------------------------------
 * 微信登录-回调页面
 * ----------------------------------------------------------------
 * [访问URL]
 * http://mysite.com/partner_login/callback.php
 * ----------------------------------------------------------------
 * [功能描述]
 * 用于获取第三方用户信息
 * ----------------------------------------------------------------
 * [参数说明]
 * goback_url  string  跳转回网站的地址
 * code        string  授权后的Code码
 * ----------------------------------------------------------------
 * @author Corwien
 * @version 2016-05
 * ----------------------------------------------------------------
 */
 
 // 微信服务号
 $appid = 'wx7a3a9bdf85d16731';
 $secret = '6a63ab4615a94db71a05194512b2cb09';

// 获取code码
$code       = isset($_GET['code']) ? $_GET['code'] : false;
$goback_url = isset($_GET['goback_url']) ? $_GET['goback_url'] : null;

// 校验code码
if(empty($code))
{
  die('Not allow!');
}

if($code && $goback_url)
{

  // 获取Token信息
  $token_info = get_token(array('appid'=>$appid, 'secret'=>$secret, 'code'=>$code));
//echo '<pre>';
//print_r($token_info);
//echo '</pre>';
  // 判断授权是否成功
  if(empty($token_info['access_token']) || empty($token_info['openid']) || empty($token_info['unionid']))
  {  
    die('授权Token失败！');  
  }
  
  // 获取用户信息
  $user = get_user($token_info);
echo '<pre>';
print_r($user);
echo '</pre>';   
  $user = eval('return ' . mb_convert_encoding(var_export($user,true), 'GBK', 'UTF-8') . ';');
  
  // 微信的用户信息获取到手了，然后接下来处理自己的业务逻辑，保存相关的信息，然后登陆自己的网站
  // ...
  // ...
  // ...

}
else
{
  die('404');

}


function get_token($options)
{
  $appid = $options['appid'];
  $secret = $options['secret'];
  $code = $options['code'];
  $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
  $curl = curl_init ();  
  curl_setopt ( $curl, CURLOPT_URL, $url );  
  curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );  
  curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );  
  curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );  
  $output = curl_exec ( $curl );  
  curl_close ( $curl ); 
  return json_decode($output, true);
}



function get_user($options)
{
  $access_token = $options['access_token'];
  $openid = $options['openid'];
 // $get_user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";

  $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
  $curl = curl_init ();  
  curl_setopt ( $curl, CURLOPT_URL, $url );  
  curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );  
  curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );  
  curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );  
  $output = curl_exec ( $curl );  
  curl_close ( $curl );
  return json_decode($output, true);
}
?>