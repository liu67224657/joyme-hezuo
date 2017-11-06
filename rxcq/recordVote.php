<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/7/31
 * Time: 15:13
 */
$forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);
if(preg_match("/mobile/",$forasp)){
    die("只能通过PC端访问！");
}
include_once("config/config.php");

$id = intval(!empty($_POST['id'])?$_POST['id']:'');
$flag = !empty($_POST['flag'])?$_POST['flag']:'';

//IP号
$ip = $_SERVER["REMOTE_ADDR"];
//键名
$key = 'HezuoRxcqKey_'.$id;
$ipkey = 'HezuoRxcqIpKey_'.$ip;

if($id>0 && $flag){
    $memcach = new memcache();
    $memcach->connect($config['mem_host'],$config['mem_port']) or die('连接失败');
    //是否投过票
    if($memcach->get($ipkey)){
        $data = array("rs"=>2,'msg'=>'error');
    }else{
        $result = $memcach->increment($key,1);
        if(!$result){
            $result = $memcach->add($key,1);
        }
        if($result){
            $memcach->add($ipkey,1);
            $data = array("rs"=>0,'msg'=>'success');
        }else{
            $data = array("rs"=>1,'msg'=>'fail');
        }
    }
    $memcach->close();
}else{
    $data = array("rs"=>3,'msg'=>'error');
}
echo json_encode($data);
exit;

