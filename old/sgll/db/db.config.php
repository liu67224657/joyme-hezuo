<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/6/19
 * Time: 18:08
 */

//数据库配置
$strpos = explode(".",$_SERVER['SERVER_NAME']);
$domain = $strpos[2];

if($domain == 'com'){

    $pathkey ='prod';
    $config['host'] = 'alyweb005.prod';
    $config['port'] = 3306;
    $config['username'] = 'wikipage';
    $config['password'] = 'wikipage2015';
    $config['db'] = 'hezuo';
    $config['table'] = 'sgll';
    $config['mem_host'] = 'alyweb004.prod';

}elseif($domain == 'beta'){

    $pathkey ='beta';
    $config['host'] = 'alyweb002.prod';
    $config['port'] = 3306;
    $config['username'] = 'wikipage';
    $config['password'] = 'wikipage';
    $config['db'] = 'hezuo';
    $config['table'] = 'sgll';
    $config['mem_host'] = 'alyweb008.prod';

}elseif($domain == 'dev'){

    $pathkey ='dev';
    $config['host'] = '172.16.75.65';
    $config['port'] = 3306;
    $config['username'] = 'root';
    $config['password'] = '654321';
    $config['db'] = 'hezuo';
    $config['table'] = 'sgll';
    $config['mem_host'] = '172.16.75.61';

}else{

    $pathkey ='alpha';
    $config['host'] = '172.16.75.65';
    $config['port'] = 3306;
    $config['username'] = 'root';
    $config['password'] = '654321';
    $config['db'] = 'hezuo';
    $config['table'] = 'sgll';
    $config['mem_host'] = '172.16.75.61';

}

$config['mem_port'] = 11211;

global $config;

//include_once('/opt/www/joymephplib/'.$pathkey.'/phplib.php');
include_once('D:\joyme\commonLib\trunk\phplib.php');
