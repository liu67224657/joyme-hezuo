<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/8/3
 * Time: 12:43
 */
include_once("config/config.php");

$ip = $_SERVER["REMOTE_ADDR"];
//键名
$ipkey = 'HezuoRxcqIpKey_'.$ip;

$key1 = 'HezuoRxcqKey_1';
$key2 = 'HezuoRxcqKey_2';
$key3 = 'HezuoRxcqKey_3';

$key11 = 'HezuoRxcqUrlKey_1';
$key12 = 'HezuoRxcqUrlKey_2';
$key13 = 'HezuoRxcqUrlKey_3';
$key14 = 'HezuoRxcqUrlKey_4';
$key15 = 'HezuoRxcqUrlKey_5';
$key16 = 'HezuoRxcqUrlKey_6';

@$flag = $_GET['flag'];

$memcach = new memcache();
$memcach->connect($config['mem_host'],$config['mem_port']) or die('链接失败');

if($flag == 'ip'){
    $memcach->delete($ipkey);
    echo "IP清除完成<br/><br/>";

}elseif($flag == 'vote'){

    $memcach->delete($key1);
    echo "一号投票数据清除完成<br/>";
    $memcach->delete($key2);
    echo "二号投票数据清除完成<br/>";
    $memcach->delete($key3);
    echo "三号投票数据清除完成<br/><br/>";

}elseif($flag == 'image'){

    $memcach->delete($key11);
    echo "一号图片数据清除完成<br/>";
    $memcach->delete($key12);
    echo "一号图片数据清除完成<br/>";
    $memcach->delete($key13);
    echo "一号图片数据清除完成<br/>";
    $memcach->delete($key14);
    echo "一号图片数据清除完成<br/>";
    $memcach->delete($key15);
    echo "一号图片数据清除完成<br/>";
    $memcach->delete($key16);
    echo "一号图片数据清除完成<br/>";
}

$memcach->close();