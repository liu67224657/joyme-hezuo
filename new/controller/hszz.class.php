<?php
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'app.class.php');
use Joyme\core\Request;
use Joyme\core\Log;
use Joyme\core\JoymeUser;

class hszz extends app{

    //页面展示
    public function index(){

        render('','web','hszz/index');
    }

    //接收提交数据页面
    public function addData(){

        JoymeUser::initByRequest();
        $pvp_division = intval(Request::post('pvp_division'));
        $pvp_team = addslashes(Request::post('pvp_team'));
        $pvp_usruid = addslashes(Request::post('pvp_usruid'));
        $pvp_qq = intval(Request::post('pvp_qq'));
        $joyme_uid = JoymeUser::getUid();
        $wgIsLogin = JoymeUser::isLogin();
        if($wgIsLogin && !empty($joyme_uid)&& !empty($pvp_division) && !empty($pvp_team) && !empty($pvp_usruid) && !empty($pvp_qq)){
            $model = M('Hszz');
            if($model->findUserInfoById( $joyme_uid )){
                $result = $model->updateUserInfo( $pvp_division,$pvp_team,$pvp_usruid,$pvp_qq ,$joyme_uid);
            }else{
                $result = $model->addUserInfo($pvp_division,$pvp_team,$pvp_usruid,$pvp_qq ,$joyme_uid);
            }
            self::outputResult($result);
        }else{
            self::optPutEmptyParam();
        }
    }

    //查找数据存在
    public function exists(){

        $pvp_usruid = addslashes(Request::post('pvp_usruid'));
        $pvp_qq = addslashes(Request::post('pvp_qq'));
        $model = M('Hszz');
        $result = false;
        if($pvp_usruid){
            $result = $model->fieldValueExists( array('pvp_usruid'=>$pvp_usruid));
        }elseif($pvp_qq){
            $result = $model->fieldValueExists( array('pvp_qq'=>$pvp_qq));
        }else{
            self::optPutEmptyParam();
        }
        self::outputResult($result);
    }
}
