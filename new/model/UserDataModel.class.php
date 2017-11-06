<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/9/7
 * Time: 15:54
 */
if (!defined('IN'))
    die('bad request');

use Joyme\db\JoymeModel;

class UserDataModel extends JoymeModel{


    //表字段
    public $fields = array(
    );
    //数据表名称
    public $tableName = 'user_info';

    //数据库配置

    /*
     * 构造函数
     */
    public function __construct() {

        $this->db_config = array(
            'hostname' => $GLOBALS['config']['db']['db_host'],
            'username' => $GLOBALS['config']['db']['db_user'],
            'password' => $GLOBALS['config']['db']['db_password'],
            'database' => $GLOBALS['config']['db']['db_name']
        );
        parent::__construct();
    }

    public function addUserInfo($mobile='',$wechart='',$qq='',$ip='',$activity_id=0){

        $data = array(
            'mobile'=>$mobile,
            'wechart'=>$wechart,
            'qq'=>$qq,
            'ip'=>$ip,
            'activity_id'=>$activity_id,
            'createtime'=>time()
        );
        return $this->insert($data);
    }


    function checkMobile($mobile){

        $where = array(
            'mobile'=>$mobile
        );
        $fields = 'mobile';
        return $this->selectRow($fields,$where);

    }
}