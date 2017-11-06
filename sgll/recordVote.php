<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/6/19
 * Time: 10:56
 */
$forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);
if(preg_match("/mobile/",$forasp)){
    die("只能通过PC端访问！");
}
include_once("db/db.config.php");

//接受参数
$id = intval(!empty($_POST['vote_num'])?$_POST['vote_num']:'');
$flag = !empty($_POST['flag'])?$_POST['flag']:'';

if($flag == 'joymevote'){
    //日期
    $date = date("Y-m-d",time());
    //接受访问IP号
    $ip = $_SERVER["REMOTE_ADDR"];
    //日志目录
    $logpath = 'log/';
    //记录IP文件
    $ip_log_file = $logpath.$date."_ip_log.txt";

    //默认投票机会为true;
    $opportunity = true;
    //程序开始判断
    //判断是否还有机会投票
    $ip_log = @fopen($ip_log_file,"r");
    $single_vote=explode("|", @fgets($ip_log));
    @fclose($ip_log);//写入成功，关闭文件
    $j = 0;
    for ($i=0; $i<=count($single_vote)-1; $i++)
    {
        $ipinfo = explode("/",$single_vote[$i]);
        if($ip == $ipinfo[0]){
            $j++;
        }
        if($j>=3){
            $opportunity = false;
            break;
        }
    }
    if($opportunity){

        include_once("voteModel.class.php");
        $model = new voteModel();
        //如果投票成功
        if($model->addVoteNum($id)){
            //添加memcache锁
            $memcache = new Memcache;
            $memcache->addServer($config['mem_host'],$config['mem_port']);
            $key = 'HezuoSgllLock';
            $ret = $memcache->add($key, 1);
            if ($ret == false) {
                usleep(100);
            } else {
                $ip_log = fopen($ip_log_file,"a");
                fwrite($ip_log,$ip."/".date("Y-m-d H:i:s",time())."/".$id."|");
                fclose($ip_log);//写入成功，关闭文件
                //释放锁
                $memcache->delete($key);
            }
            $data = array("rs"=>0,'msg'=>'投票成功');
        }else{
            $data = array("rs"=>1,'msg'=>'投票失败');
        }
    }else{
        $data = array("rs"=>2,'msg'=>'机会已用完');
    }
}else{
    $data = array("rs"=>3,'msg'=>'非法投票');
}
echo json_encode($data);
exit;