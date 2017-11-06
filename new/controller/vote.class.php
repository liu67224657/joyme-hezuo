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

class vote extends free{

    protected static $token = null;
    protected static $serialNumber = null;
    protected static $activityId = null;
    protected static $votingNumber = 1;
    protected static $voteIp = null;
    protected static $requestTime = null;
    protected static $callback = null;
    protected static $secretKey = 'JOYME-HEZUO-VOTE';

    //设置token
    public static function setToken($token){
        self::$token = $token;
    }

    //设置tou投票编号
    public static function setSerialNumber($serialNumber){
        self::$serialNumber = $serialNumber;
    }

    //设置投票数量
    public static function setVotingNumber($votingNumber){
        self::$votingNumber = $votingNumber;
    }

    //设置活动ID
    public static function setActivityId($activityId){
        self::$activityId = $activityId;
    }

    //设置投票IP
    public static function setVoteIp($voteIp){
        self::$voteIp = $voteIp;
    }

    //设置请求时间
    public static function setRequestTime(){
        self::$requestTime = time();
    }

    //设置callback
    public static function setCallbck($callback){
        self::$callback = $callback;
    }

    //token生成规则
    public static function generateToken(){

        return md5(self::$secretKey.self::$activityId.self::$votingNumber.self::$serialNumber);
    }

    //token验证
    public static function checkToken(){

        if(!is_null(self::$token) && self::$token === self::generateToken()){
            return true;
        }
        self::outputTokenFail(self::$callback);
    }

    //初始化方法
    public static function init($token,$serialNumber,$activityId,$votingNumber,$voteIp,$callback = null){

        self::setToken($token);
        self::setSerialNumber($serialNumber);
        self::setVotingNumber($votingNumber);
        self::setActivityId($activityId);
        self::setVoteIp($voteIp);
        self::setRequestTime();
        self::setCallbck($callback);
    }

    //投票接口
    function getVoteInfo(){

        $token = Request::get('token');
        $serialNumber = Request::get('serialNumber');
        $activityId = Request::get('activityId');
        $votingNumber = Request::get('votingNumber');
        $voteIp = Request::get('voteIp');
        $callback = Request::get('callback');
        //初始化数据
        self::init($token,$serialNumber,$activityId,$votingNumber,$voteIp,$callback);
        //检验token是否正确
//        self::checkToken();
        //检查机会
        $num = self::checkOpportunity();
        //成功之后入库操作
        self::recordData($num);
    }

    //是否还有机会
    public static function checkOpportunity(){

        //判断活动ID(规则不一)
        if(self::$activityId == 1){
            $model = M('VoteInfo');
            //如果是1号或者2号投票编号
            if(self::$serialNumber == 1 || self::$serialNumber == 2){
                $num = $model->checkOpportunityByIp(self::$activityId,self::$voteIp,array(1,2));
                if($num>=1){
                    //1,2编号没有机会
                    self::outputOpportunity(self::$callback);
                }
            }else{
                if($model->checkRepeatBySerNumber(self::$activityId,self::$voteIp,self::$serialNumber)>0){
                    self::outputOpportunity(self::$callback,true);
                }else{
                    $num = $model->checkOpportunityByIp(self::$activityId,self::$voteIp,array(3,4,5,6,7,8,9,10,11,12),self::$serialNumber);
                    if($num>=3){
                        //其他编号没有机会
                        self::outputOpportunity(self::$callback);
                    }
                }
            }
            return $num;
        }
    }

    //数据入库
    public static function recordData($num = false){

        $model = M('VoteInfo');
        $countmodel = M('VoteCount');
        $result = $model->addvoteInfo(self::$serialNumber,self::$activityId,self::$votingNumber,self::$voteIp,self::$requestTime);
        if($result){
            $countmodel->addCountNumByNumber(self::$serialNumber,self::$activityId,self::$votingNumber);
        }
        self::outputResult($result,self::$callback,$num);
    }
}