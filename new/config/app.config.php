<?php
$GLOBALS['config']['site_name'] = 'LazyPHP3';
$GLOBALS['config']['site_domain'] = 'lazyphp3.sinaapp.com';

//设置时区
date_default_timezone_set('PRC');
//七牛图片地址

//需要跳转的环境
$strpos = explode(".",$_SERVER['SERVER_NAME']);
$GLOBALS['domain'] = $strpos[2];

if($GLOBALS['domain']=='dev'){
    $config['reids_host'] = '172.16.75.30';
    $config['reids_port'] = 6380;
    $wgQiNiuPath = 'joymetest.joyme.com';
    $pathkey ='dev';
}
if($GLOBALS['domain']=='alpha'){
    $config['reids_host'] = '172.16.75.32';
    $config['reids_port'] = 6379;
    $wgQiNiuPath = 'joymetest.qiniudn.com';
    $pathkey ='alpha';
}
if($GLOBALS['domain']=='beta'){
    $config['reids_host'] = 'alyweb008.prod';
    $config['reids_port'] = 6380;
    $wgQiNiuPath = 'joymepic.joyme.com';
    $pathkey ='beta';
}
if($GLOBALS['domain']=='com'){
    $config['reids_host'] = 'alyweb001.prod';
    $config['reids_port'] = 6380;
    $wgQiNiuPath = 'joymepic.joyme.com';
    $pathkey ='prod';
}

$GLOBALS['redis_host'] = $config['reids_host'];
$GLOBALS['redis_port'] = $config['reids_port'];
$GLOBALS['static_url'] = 'http://static.joyme.'.$GLOBALS['domain'];

//配置加载PHP公共库的具体路径
$GLOBALS['libPath'] = '/opt/www/joymephplib/'.$pathkey.'/phplib.php';

