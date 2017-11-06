<?php
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'app.class.php');
use Joyme\core\Request;
use Joyme\core\Log;
use Joyme\core\JoymeUser;

class cq extends app{

    public $provices = array(
        '1' => '北京市',
        '2' => '天津市',
        '3' => '上海市',
        '4' => '重庆市',
        '5' => '河北省',
        '6' => '山西省',
        '7' => '辽宁省',
        '8' => '吉林省',
        '9' => '黑龙江省',
        '10' => '江苏省',
        '11' => '浙江省',
        '12' => '安徽省',
        '13' => '福建省',
        '14' => '江西省',
        '15' => '广东省',
        '16' => '山东省',
        '17' => '河南省',
        '18' => '湖北省',
        '19' => '湖南省',
        '20' => '甘肃省',
        '21' => '四川省',
        '22' => '贵州省',
        '23' => '海南省',
        '24' => '云南省',
        '25' => '青海省',
        '26' => '陕西省',
        '27' => '广西',
        '28' => '西藏',
        '29' => '宁夏',
        '30' => '新疆',
        '31' => '内蒙古',
        '32' => '台湾',
        '33' => '香港',
        '34' => '澳门',
    );

    //页面展示
    public function index(){

        global $GLOBALS;
        $data['item'] = $this->provices;
        render($data,'web','cq/index');
    }

    //接收提交数据页面
    public function addData(){

        JoymeUser::initByRequest();
        $pvp_division = intval(Request::post('pvp_division'));
        $pvp_team = addslashes(Request::post('pvp_team'));
        $pvp_usruid = addslashes(Request::post('pvp_usruid'));
        $pvp_qq = intval(Request::post('pvp_qq'));
        $pvp_tel = intval(Request::post('pvp_tel'));
        $pvp_age = intval(Request::post('pvp_age'));
        $pvp_gender = intval(Request::post('pvp_gender'));
        $pvp_area = intval(Request::post('pvp_area'));
        $joyme_uid = JoymeUser::getUid();
        $wgIsLogin = JoymeUser::isLogin();
        if($wgIsLogin && !empty($joyme_uid)&& !empty($pvp_division) && !empty($pvp_team) && !empty($pvp_usruid) && !empty($pvp_qq) && !empty($pvp_tel)){
            $model = M('Cq');
            if($model->findUserInfoById( $joyme_uid )){
                $result = $model->updateUserInfo( $pvp_division,$pvp_team,$pvp_usruid,$pvp_qq ,$pvp_tel,$pvp_age,$pvp_gender,$joyme_uid,$pvp_area);
            }else{
                $result = $model->addUserInfo($pvp_division,$pvp_team,$pvp_usruid,$pvp_qq ,$pvp_tel,$pvp_age,$pvp_gender,$joyme_uid,$pvp_area);
            }
            self::outputResult($result);
        }else{
            self::optPutEmptyParam();
        }
    }

    //查找数据存在
    public function exists(){

        $pvp_team = addslashes(Request::post('pvp_team'));
        $pvp_usruid = addslashes(Request::post('pvp_usruid'));
        $pvp_qq = addslashes(Request::post('pvp_qq'));
        $pvp_tel = addslashes(Request::post('pvp_tel'));
        $model = M('Cq');
        $result = false;
        if($pvp_team){
            $result = $model->fieldValueExists( array('pvp_team'=>$pvp_team) );
        }elseif($pvp_usruid){
            $result = $model->fieldValueExists( array('pvp_usruid'=>$pvp_usruid)  );
        }elseif($pvp_qq){
            $result = $model->fieldValueExists( array('pvp_qq'=>$pvp_qq)  );
        }elseif($pvp_tel){
            $result = $model->fieldValueExists( array('pvp_tel'=>$pvp_tel)  );
        }else{
            self::optPutEmptyParam();
        }
        self::outputResult($result);
    }
}
