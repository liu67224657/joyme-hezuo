<?php
if (!defined('IN')) die('bad request');

include_once( AROOT . 'controller' . DS . 'free.class.php' );

use Joyme\core\Request;

class interesttesing extends free{

    //趣味测试Api
    function index(){

        global $GLOBALS;

        $vals = Request::get("vals");
        $len = Request::get("len");;
        $callback = Request::get('callback');

        $key = 'warcraft';
        $Prefix = $key."|".$GLOBALS['domain']."|". __CLASS__ ."| ".$vals;

        $redis = new Redis();
        $redis->connect($GLOBALS['redis_host'], $GLOBALS['redis_port']);
        if($redis){
            if(!$redis->exists($Prefix) || ($redis->exists($Prefix) && $redis->get($Prefix)=='')){
                $num = mt_rand(0,$len);
                if($num>=0){
                    $redis->set($Prefix,$num);
                    $redis->expire($Prefix,$this->expiresTestingTime());
                    $result = array('rs'=>1,'msg'=>'success !','data'=>$num);
                }else{
                    $result = array('rs'=>0,'msg'=>'rand error!','data'=>'');
                }
            }else{
                $num = $redis->get($Prefix);
                $result = array('rs'=>1,'msg'=>'success !','data'=>$num);
            }
        }else{
            $result = array('rs'=>0,'msg'=>'redis error!','data'=>'');
        }
        if(strlen($callback) > 1){
            echo $callback . "([" . json_encode($result) . "])";
        }else{
            echo $callback.'( '.json_encode($result).' )';
        }
        exit();
    }


    //计算有效期
    function expiresTestingTime(){
        //获取零点的时间戳
        $time = mktime(00,00,00,date("m"),date("d")+1,date("Y"))-time();
        return $time;
    }
}