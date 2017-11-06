<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/10/22
 * Time: 14:31
 */
if (!defined('IN')) die('bad request');

include_once( AROOT . 'controller' . DS . 'app.class.php' );

use Joyme\core\Request;

class xhzstools extends app{

    //星河活动ID为1
    private static $activityId = 1;

    //后台主页面
    public function showPage(){

        $string = <<<EOD
            <html>
            <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
                <title>星河战神</title>
            </head>
            <body>
            <h1>星河战神活动后台</h1>
            <ul>
                <li><a href="?c=xhzstools&a=showSetVoteNumPage">设置投票数量</a></li>
                <li><a href="?c=xhzstools&a=showSetTimePage">设置活动时间</a></li>
            </ul>
            </body>
            </html>
EOD;
        echo $string;
    }

    //设置投票数量页
    public function showSetVoteNumPage(){

        global $GLOBALS;
        $data['static_js_url'] = $GLOBALS['static_url'];
        $model = M('VoteCount');
        $voteData = $model->selectAllByActivityId(self::$activityId);
        $html = '<a href="?c=xhzstools&a=showPage">返回</a>';
        if(!empty($voteData)){
            $html.= "<table border='1' class='bt1' style='width: 400px;'><tr><td colspan='5' align='center'><h3>投票结果</h3></td></tr><tr><td align='center'>序号</td><td align='center'>票数</td><td align='center'></td><td align='center'>操作</td></td></tr>";
            foreach($voteData as $k=>$row){
                $sort = $row['serian_number'];
                $html.="<tr><td align='center'>".$sort."</td><td align='center'>".$row['count_num']."</td><td align='center'><input type='text'style='width: 30px;' id=$sort></td><td align='center'><input type='button'style='width: 40px;' value='修改'  onclick='uplNum($sort)'></td></tr>";
            }
        }
        $data['html'] = $html;
        render($data,'web','activity/xhzs/setnum');
    }

    //设置活动时间
    public function showSetTimePage(){

        $redis = new Redis();
        $redis->connect($GLOBALS['redis_host'],$GLOBALS['redis_port']);
        echo '<a href="?c=xhzstools&a=showPage">返回</a><br>';
        if($_POST){
            $jjpktime = Request::post('jjpk');
            $jxdstime = Request::post('jxds');
            if(strlen($jjpktime)==8){
                //机甲PK时间设置
                $str = str_split($jjpktime,2);
                $jjpktime = $str[0].$str[1].'-'.$str[2].'-'.$str[3].' 00:00:00';
                if($redis->set('HZ_japk_end_time',$jjpktime)){
                    echo '设置成功<br>';
                }
            if(strlen($jxdstime)==8){
                //竞选大赛时间设置
                $str = str_split($jxdstime,2);
                $jxdstime = $str[0].$str[1].'-'.$str[2].'-'.$str[3].' 00:00:00';
                   if($redis->set('HZ_jxds_start_time',$jxdstime)){
                       echo '设置成功<br>';
                   }
            }
            }
        }
        if($redis->get('HZ_japk_end_time')){
            echo '机甲截止时间设置为:'.$redis->get('HZ_japk_end_time').'</br>';
        }else{
            echo '机甲开始时间设置为:2015-11-12 00:00:00</br>';
        }

        if($redis->get('HZ_jxds_start_time')){
            echo '竞选当前时间设置为:'.$redis->get('HZ_jxds_start_time');
        }else{
            echo '机甲开始时间设置为:2015-11-20 00:00:00';
        }

        echo '<br>';
        echo '<br>';
        echo '<br>';
        $string = <<<EOD
        <html>
         <head>
          <title>星河战神</title>
          <meta name="Author" content="">
          <meta name="Keywords" content="">
          <meta name="Description" content="">
         </head>
         <body>
            <form method="post" action="/new/?c=xhzstools&a=showSetTimePage">
            设置机甲PK结束时间:
            <p><input type="text" name="jjpk" value='20151112'></p>
            <p>设置竞猜开始时间:</p>
            <input type="text" name="jxds" value='20151027'>
            <p><input type="submit"></p>
            </form>
         </body>
        </html>
EOD;
            echo $string;
    }

    //处理票数方法
    public function setVoteNum(){

        $model = M('VoteCount');
        $num = Request::get('num');
        $serianNumber = Request::get('id');
        $activity_id = Request::get('activity_id');
        $callback = Request::get('callback');
        if(is_numeric($num) && !empty($serianNumber) && !empty($activity_id)){
            $result = $model->updateOneCountNum($serianNumber,$activity_id,$num);
            self::outputResult($result,$callback);
        }
    }

    //清除投票记录，测试使用
    public function clearip(){

        $ip = $_SERVER["REMOTE_ADDR"];
        if(empty($ip)){
            self::optPutEmptyParam();
        }
        $model = M('VoteInfo');
        if($model->clearInfo($ip)){
            echo 'OK';
        }else{
            echo 'NO';
        }
        exit;
    }
}