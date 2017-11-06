<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/10/23
 * Time: 17:56
 */
if (!defined('IN')) die('bad request');

include_once( AROOT . 'controller' . DS . 'free.class.php' );

use Joyme\core\Request;

class Userinfo extends free{

    //接受数据
    function addUserData(){

        $wechart = Request::get('wechart');
        $mobile = Request::get('mobile');
        $qq = Request::get('qq');
        $ip = Request::get('ip');
        $callback = Request::get('callback');
        $activity_id = Request::get('activityId');
        if(empty($activity_id)){
            self::optPutEmptyParam($callback='');
        }else{
            $model = M('UserData');
            $result = $model->addUserInfo($mobile,$wechart,$qq,$ip,$activity_id);
            self::outputResult($result,$callback);
        }
    }

    //检测手机号是否已存在
    function checkMobile(){

        $mobile = Request::post('mobile');
        $model = M('UserData');
        $result = $model->checkMobile($mobile);
        self::outputResult($result);
    }
}