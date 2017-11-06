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

    //���ֶ�
    public $fields = array(
    );
    //���ݱ�����
    public $tableName = 'count_activity_vaote';

    //���ݿ�����

    /*
     * ���캯��
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

    //�����ܼ���
    public function addCountNumByNumber($serialNumber,$activityId,$votingNumber){

        $where = array(
            'serian_number'=>$serialNumber,
            'activity_id'=>$activityId
        );
        return $this->numChange('count_num',$where,$votingNumber);
    }


    //��ѯ����
    public function selectAllByActivityId($activityId,$limit = 20,$skip=0){

        $where = array(
            'activity_id' => $activityId,
        );
        $fields = 'serian_number,count_num';
        $order = 'serian_number';
        return $this->select($fields, $where, $order, $limit, $skip);
    }

    //�޸ĵ���
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