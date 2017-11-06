<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/9/6
 * Time: 18:03
 */
if (!defined('IN')) die('bad request');

include_once( AROOT . 'controller' . DS . 'free.class.php' );

use Joyme\core\Request;

class activity extends free{

    //星河活动ID为1
    private static $activityId = 1;
    private static $japk_end_time = '2015-11-12 00:00:00';
    private static $jxds_start_time = '2015-11-20 00:00:00';


    //星河战神活动
    function xhzs(){

        global $GLOBALS;
        $data['static_url'] = $GLOBALS['static_url'].'/pc/hezuo/xhzs';
        $data['static_js_url'] = $GLOBALS['static_url'];
        $forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);

        if(preg_match("/mobile/",$forasp)){
            render($data,'web','activity/mobile');
        }else{
            render($data,'web','activity/index');
        }
    }


    //星河投票活动--游戏玩法
    function yxwf(){

        global $GLOBALS;
        $data['static_url'] = $GLOBALS['static_url'].'/pc/hezuo/xhzs';
        $data['static_js_url'] = $GLOBALS['static_url'];
        render($data,'web','activity/xhzs/yxwf');
    }

    //机甲PK
    function japk(){

        $forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);
        if(preg_match("/mobile/",$forasp)){
            die("只能通过PC端访问！");
        }
        global $GLOBALS;
        $model = M('VoteCount');
        $voteData = $model->selectAllByActivityId(self::$activityId);
        $data['serian_number1'] = $voteData[0]['count_num'];
        $data['serian_number2'] = $voteData[1]['count_num'];
        $data['static_url'] = $GLOBALS['static_url'].'/pc/hezuo/xhzs';
        $data['static_js_url'] = $GLOBALS['static_url'];
        $data['user_ip'] = $_SERVER["REMOTE_ADDR"];

        $redis = new Redis();
        $redis->connect($GLOBALS['redis_host'],$GLOBALS['redis_port']);

        if($redis->get('HZ_japk_end_time')){
            $time = $redis->get('HZ_japk_end_time');
        }else{
            $time = self::$japk_end_time;
        }

        if(time()>=strtotime($time)){
            $data['end_time_flag'] = 2;
        }else{
            $data['end_time_flag'] = 1;
        }
        render($data,'web','activity/xhzs/japk');
    }

    //论坛晒图
    function ltst(){

        global $GLOBALS;
        $data['static_url'] = $GLOBALS['static_url'].'/pc/hezuo/xhzs';
        $data['static_js_url'] = $GLOBALS['static_url'];
        render($data,'web','activity/xhzs/ltst');
    }

    //精选大奖
    function jxdj(){

        $forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);
        if(preg_match("/mobile/",$forasp)){
            die("只能通过PC端访问！");
        }
        global $GLOBALS;
        $model = M('VoteCount');
        $voteData = $model->selectAllByActivityId(self::$activityId);
        $data['serian_number3'] = $voteData[2]['count_num'];
        $data['serian_number4'] = $voteData[3]['count_num'];
        $data['serian_number5'] = $voteData[4]['count_num'];
        $data['serian_number6'] = $voteData[5]['count_num'];
        $data['serian_number7'] = $voteData[6]['count_num'];
        $data['serian_number8'] = $voteData[7]['count_num'];
        $data['serian_number9'] = $voteData[8]['count_num'];
        $data['serian_number10'] = $voteData[9]['count_num'];
        $data['static_url'] = $GLOBALS['static_url'].'/pc/hezuo/xhzs';
        $data['static_js_url'] = $GLOBALS['static_url'];
        $data['user_ip'] = $_SERVER["REMOTE_ADDR"];
        $redis = new Redis();
        $redis->connect($GLOBALS['redis_host'],$GLOBALS['redis_port']);
        if($redis->get('HZ_jxds_start_time')){
            $time = $redis->get('HZ_jxds_start_time');
        }else{
            $time = self::$jxds_start_time;
        }
        if(time()<strtotime($time)){
            $data['end_time_flag'] = 2;
        }else{
            $data['end_time_flag'] = 1;
        }
        render($data,'web','activity/xhzs/jcxs');
    }

    //决赛直播
    function jszb(){

        global $GLOBALS;
        $data['static_url'] = $GLOBALS['static_url'].'/pc/hezuo/xhzs';
        $data['static_js_url'] = $GLOBALS['static_url'];
        render($data,'web','activity/xhzs/tgaj');
    }
}