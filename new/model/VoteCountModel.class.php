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

class VoteCountModel extends JoymeModel{

    //表字段
    public $fields = array(
    );
    //数据表名称
    public $tableName = 'count_activity_vaote';

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

    //增加总计数
    public function addCountNumByNumber($serialNumber,$activityId,$votingNumber){

        $where = array(
            'serian_number'=>$serialNumber,
            'activity_id'=>$activityId
        );
        return $this->numChange('count_num',$where,$votingNumber);
    }


    //查询所有
    public function selectAllByActivityId($activityId,$limit = 20,$skip=0){

        $where = array(
            'activity_id' => $activityId,
        );
        $fields = 'serian_number,count_num';
        $order = 'serian_number';
        return $this->select($fields, $where, $order, $limit, $skip);
    }

    //修改单个
    public function updateOneCountNum($serianNumber,$activity_id,$num){

        $where = array(
            'serian_number'=>$serianNumber,
            'activity_id'=>$activity_id
        );
        $data = array(
            'count_num'=>$num
        );
        return $this->update($data, $where);
    }
}