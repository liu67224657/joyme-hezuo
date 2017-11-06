<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/6/19
 * Time: 15:42
 */
$forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);
if(preg_match("/mobile/",$forasp)){
    die("只能通过PC端访问！");
}
include_once("db/db.config.php");
use Joyme\core\Log;

//接受访问IP号
$ip = $_SERVER["REMOTE_ADDR"];
//日志目录
$logpath = 'log/';
$vote_data_file = $logpath."user_info_log.txt";

//接受参数
$username = !empty($_POST['username'])?$_POST['username']:'';
$phone = !empty($_POST['phone'])?$_POST['phone']:'';
$qq = !empty($_POST['qq'])?$_POST['qq']:'';
$address = !empty($_POST['address'])?$_POST['address']:'';
$flag = !empty($_POST['flag'])?$_POST['flag']:'';

if($flag == 'joymevote'){
    if($username && $phone && $qq && $address){

        Log::config(Log::INFO);
        Log::info('三国来了抽奖用户信息',"姓名:".$username." 手机:".$phone." QQ:".$qq." 地址:".$address  );
        $data = array("rs"=>0,'msg'=>'投票成功');
    }else{
        $data = array("rs"=>1,'msg'=>'投票失败');
    }
}else{
    $data = array("rs"=>3,'msg'=>'非法投票');
}
echo json_encode($data);
exit;