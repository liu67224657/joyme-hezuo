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

class VoteInfoModel extends JoymeModel{

    //���ֶ�
    public $fields = array(
    );
    //���ݱ�����
    public $tableName = 'activity_vote';

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

    //�������
    public function addvoteInfo($serialNumber,$activityId,$votingNumber,$voteIp,$requestTime){

        $data = array(
            'serian_number'=>$serialNumber,
            'voting_number'=>$votingNumber,
            'vote_ip'=>$voteIp,
            'activity_id'=>$activityId,
            'create_time'=>$requestTime,
        );
        return $this->insert($data);
    }

    //���ݱ�š�IP��ѯ����
    public function checkOpportunityByIp($activityId,$voteIp,$arrNumber){

        $where = array(
            'vote_ip' => $voteIp,
            'activity_id' => $activityId,
            'serian_number' =>array('in',$arrNumber)
        );
        return $this->count($where);
    }

    //��ѯ�Ƿ��Ѿ�ͶƱ
    public function checkRepeatBySerNumber($activityId,$voteIp,$serialNumber){

        $where = array(
            'vote_ip' => $voteIp,
            'activity_id' => $activityId,
            'serian_number' => $serialNumber
        );
        return $this->count($where);
    }

    //���IP��¼
    public function clearInfo($ip){

        $where = array(
            'vote_ip'=>$ip
        );
        return $this->delete($where);
    }
}