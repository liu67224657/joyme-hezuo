<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/8/3
 * Time: 12:43
 */
include_once("config/config.php");

$ip = $_SERVER["REMOTE_ADDR"];
//����
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
$memcach->connect($config['mem_host'],$config['mem_port']) or die('����ʧ��');

if($flag == 'ip'){
    $memcach->delete($ipkey);
    echo "IP������<br/><br/>";

}elseif($flag == 'vote'){

    $memcach->delete($key1);
    echo "һ��ͶƱ����������<br/>";
    $memcach->delete($key2);
    echo "����ͶƱ����������<br/>";
    $memcach->delete($key3);
    echo "����ͶƱ����������<br/><br/>";

}elseif($flag == 'image'){

    $memcach->delete($key11);
    echo "һ��ͼƬ����������<br/>";
    $memcach->delete($key12);
    echo "һ��ͼƬ����������<br/>";
    $memcach->delete($key13);
    echo "һ��ͼƬ����������<br/>";
    $memcach->delete($key14);
    echo "һ��ͼƬ����������<br/>";
    $memcach->delete($key15);
    echo "һ��ͼƬ����������<br/>";
    $memcach->delete($key16);
    echo "һ��ͼƬ����������<br/>";
}

$memcach->close();