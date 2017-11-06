<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/6/19
 * Time: 18:08
 */

//数据库配置
$strpos = explode(".",$_SERVER['HTTP_HOST']);
$domain = $strpos[2];

if($domain == 'com'){

    $config['mem_host'] = 'alyweb004.prod';

}elseif($domain == 'beta'){

    $config['mem_host'] = 'alyweb008.prod';

}else{

    $config['mem_host'] = '172.16.75.61';

}

$config['mem_port'] = 11211;

