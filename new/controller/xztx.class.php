<?php
if (!defined('IN')) die('bad request');
include_once( AROOT . 'controller' . DS . 'free.class.php' );
use Joyme\core\Request;
use Joyme\core\Log;

class xztx extends free{


    //行者天下广告数据接口
    function getAdvertInfo(){

         $appid = Request::getParam('appid'); //应用标识,由广告主提供(或者双方协商确定)
         $deviceid = Request::getParam('deviceid'); //设备标识(mac 去冒号,idfa 的一种)
         $eventtime = Request::getParam('eventtime'); //时间戳
         $source = Request::getParam('source'); //点击来源，用于区分是否我方发送的数据
         $openudid = Request::getParam('OPENUDID'); //Openudid  (ios7 提供)
         $idfa = Request::getParam('IDFA'); //idfa(ios7 提供)
         $mac = Request::getParam('mac'); //mac 值(ios7 不传)不去冒号
         $callback = Request::getParam('callback'); //激活回调 url (广告主支持 callback 则提供)
         if(empty($appid) || empty($source) || empty($idfa)){
            self::optPutEmptyParam($callback);
            exit();
         }
         $str = 'appid:'.$appid.' deviceid:'.$deviceid.' eventtime:'.date('Y-m-d H:i:s',$eventtime).' openudid:'.$openudid.' idfa:'.$idfa.' mac:'.$mac.' ';
         Log::error(__FUNCTION__,"行者天下广告数据接口",$str);
         self::outputResult(true,$callback);
    }
}