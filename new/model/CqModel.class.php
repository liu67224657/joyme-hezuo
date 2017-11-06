<?php
if (!defined('IN'))
    die('bad request');

use Joyme\db\JoymeModel;
class CqModel extends JoymeModel
{
    public $fields = array();

    public $tableName = 'cq_pvp_active';

    public function __construct()
    {
        $this->db_config = array(
            'hostname' => $GLOBALS['config']['db']['db_host'],
            'username' => $GLOBALS['config']['db']['db_user'],
            'password' => $GLOBALS['config']['db']['db_password'],
            'database' => $GLOBALS['config']['db']['db_name']
        );
        parent::__construct();
    }


    //增加用户信息
    public function addUserInfo($pvp_division,$pvp_team,$pvp_usruid,$pvp_qq ,$pvp_tel,$pvp_age,$pvp_gender,$joyme_uid,$pvp_area){

        $data = array(
            'joyme_uid'=>$joyme_uid,
            'pvp_division'=>$pvp_division,
            'pvp_team'=>$pvp_team,
            'pvp_qq'=>$pvp_qq,
            'pvp_usruid'=>$pvp_usruid,
            'pvp_tel'=>$pvp_tel,
            'pvp_age'=>$pvp_age,
            'pvp_sex'=>$pvp_gender,
            'pvp_area'=>$pvp_area,
            'time'=>time()
        );
        return $this->insert($data);
    }

    //更新用户信息
    public function updateUserInfo( $pvp_division,$pvp_team,$pvp_usruid,$pvp_qq ,$pvp_tel,$pvp_age,$pvp_gender,$joyme_uid,$pvp_area){

        $where = array(
            'joyme_uid'=>$joyme_uid
        );
        $data = array(
            'pvp_division'=>$pvp_division,
            'pvp_team'=>$pvp_team,
            'pvp_qq'=>$pvp_qq,
            'pvp_usruid'=>$pvp_usruid,
            'pvp_tel'=>$pvp_tel,
            'pvp_age'=>$pvp_age,
            'pvp_sex'=>$pvp_gender,
            'pvp_area'=>$pvp_area
        );
        return $this->update($data, $where);
    }


    //查询用户有没有游戏记录
    public function findUserInfoById( $joymeId ){

        $where = array(
            'joyme_uid'=>$joymeId
        );
        return $this->selectRow('*',$where);
    }

    //查找字段值是否存在
    function fieldValueExists( $field ){

        $where = $field;
        return $this->selectRow('*',$where);
    }
}