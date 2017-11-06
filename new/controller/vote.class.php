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

    //����token
    public static function setToken($token){
        self::$token = $token;
    }

    //����touͶƱ���
    public static function setSerialNumber($serialNumber){
        self::$serialNumber = $serialNumber;
    }

    //����ͶƱ����
    public static function setVotingNumber($votingNumber){
        self::$votingNumber = $votingNumber;
    }

    //���ûID
    public static function setActivityId($activityId){
        self::$activityId = $activityId;
    }

    //����ͶƱIP
    public static function setVoteIp($voteIp){
        self::$voteIp = $voteIp;
    }

    //��������ʱ��
    public static function setRequestTime(){
        self::$requestTime = time();
    }

    //����callback
    public static function setCallbck($callback){
        self::$callback = $callback;
    }

    //token���ɹ���
    public static function generateToken(){

        return md5(self::$secretKey.self::$activityId.self::$votingNumber.self::$serialNumber);
    }

    //token��֤
    public static function checkToken(){

        if(!is_null(self::$token) && self::$token === self::generateToken()){
            return true;
        }
        self::outputTokenFail(self::$callback);
    }

    //��ʼ������
    public static function init($token,$serialNumber,$activityId,$votingNumber,$voteIp,$callback = null){

        self::setToken($token);
        self::setSerialNumber($serialNumber);
        self::setVotingNumber($votingNumber);
        self::setActivityId($activityId);
        self::setVoteIp($voteIp);
        self::setRequestTime();
        self::setCallbck($callback);
    }

    //ͶƱ�ӿ�
    function getVoteInfo(){

        $token = Request::get('token');
        $serialNumber = Request::get('serialNumber');
        $activityId = Request::get('activityId');
        $votingNumber = Request::get('votingNumber');
        $voteIp = Request::get('voteIp');
        $callback = Request::get('callback');
        //��ʼ������
        self::init($token,$serialNumber,$activityId,$votingNumber,$voteIp,$callback);
        //����token�Ƿ���ȷ
//        self::checkToken();
        //������
        $num = self::checkOpportunity();
        //�ɹ�֮��������
        self::recordData($num);
    }

    //�Ƿ��л���
    public static function checkOpportunity(){

        //�жϻID(����һ)
        if(self::$activityId == 1){
            $model = M('VoteInfo');
            //�����1�Ż���2��ͶƱ���
            if(self::$serialNumber == 1 || self::$serialNumber == 2){
                $num = $model->checkOpportunityByIp(self::$activityId,self::$voteIp,array(1,2));
                if($num>=1){
                    //1,2���û�л���
                    self::outputOpportunity(self::$callback);
                }
            }else{
                if($model->checkRepeatBySerNumber(self::$activityId,self::$voteIp,self::$serialNumber)>0){
                    self::outputOpportunity(self::$callback,true);
                }else{
                    $num = $model->checkOpportunityByIp(self::$activityId,self::$voteIp,array(3,4,5,6,7,8,9,10,11,12),self::$serialNumber);
                    if($num>=3){
                        //�������û�л���
                        self::outputOpportunity(self::$callback);
                    }
                }
            }
            return $num;
        }
    }

    //�������
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