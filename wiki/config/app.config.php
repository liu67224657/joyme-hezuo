<?php
$GLOBALS['config']['site_name'] = 'LazyPHP3';
$GLOBALS['config']['site_domain'] = 'lazyphp3.sinaapp.com';

//设置时区
date_default_timezone_set('PRC');
//七牛图片地址

//需要跳转的环境
$strpos = explode(".",$_SERVER['SERVER_NAME']);
$GLOBALS['domain'] = $strpos[2];
$config=array();
if($GLOBALS['domain']=='dev'){
    $config['reids_host'] = '172.16.75.30';
    $config['reids_port'] = 6380;
    $config['redis_password'] = '';
    $wgQiNiuPath = 'joymetest.joyme.com';
    $pathkey ='dev';
    $secrectkey = '7ejw!9d#';
    $GLOBALS['config']['qiniu']['bucket'] = 'joymetest';
}
if($GLOBALS['domain']=='alpha'){
    $config['reids_host'] = '172.16.75.32';
    $config['reids_port'] = 6379;
    $config['redis_password'] = '';
    $wgQiNiuPath = 'joymetest.qiniudn.com';
    $pathkey ='alpha';
    $secrectkey = '8F5&JL3';
    $GLOBALS['config']['qiniu']['bucket'] = 'joymetest';
}
if($GLOBALS['domain']=='beta'){
    /*$config['reids_host'] = 'alyweb008.prod';
    $config['reids_port'] = 6380;*/
    $config['reids_host'] = 'r-2ze25cf88632c7b4.redis.rds.aliyuncs.com';
    $config['reids_port'] = 6379;
    $config['redis_password'] = 'FHW2n2Gh';
    $wgQiNiuPath = 'joymepic.joyme.com';
    $pathkey ='beta';
    $secrectkey = '#4g%klwe';
    $GLOBALS['config']['qiniu']['bucket'] = 'joymepic';
}
if($GLOBALS['domain']=='com'){
   /* $config['reids_host'] = 'alyweb001.prod';
    $config['reids_port'] = 6380;*/
    $config['reids_host'] = 'r-2zef16817404a374.redis.rds.aliyuncs.com';
    $config['reids_port'] = 6379;
    $config['redis_password'] = 'zIGMyY12';
    $wgQiNiuPath = 'joymepic.joyme.com';
    $pathkey ='prod';
    $secrectkey = 'yh87&sw2';
    $GLOBALS['config']['qiniu']['bucket'] = 'joymepic';
}

$GLOBALS['redis_host'] = $config['reids_host'];
$GLOBALS['redis_port'] = $config['reids_port'];
$GLOBALS['redis_password'] = $config['redis_password'];
$GLOBALS['static_url'] = 'http://static.joyme.'.$GLOBALS['domain'];

//配置加载PHP公共库的具体路径
$GLOBALS['libPath'] = '/opt/www/joymephplib/'.$pathkey.'/phplib.php';
//$GLOBALS['libPath'] = 'D:\wamp\www\joyme\php\joymephplib\trunk\phplib.php';
