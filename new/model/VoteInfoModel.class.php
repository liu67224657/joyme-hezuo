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

    //表字段
    public $fields = array(
    );
    //数据表名称
    public $tableName = 'activity_vote';

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

    //数据入库
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

    //根据编号、IP查询机会
    public function checkOpportunityByIp($activityId,$voteIp,$arrNumber){

        $where = array(
            'vote_ip' => $voteIp,
            'activity_id' => $activityId,
            'serian_number' =>array('in',$arrNumber)
        );
        return $this->count($where);
    }

    //查询是否已经投票
    public function checkRepeatBySerNumber($activityId,$voteIp,$serialNumber){

        $where = array(
            'vote_ip' => $voteIp,
            'activity_id' => $activityId,
            'serian_number' => $serialNumber
        );
        return $this->count($where);
    }

    //清除IP记录
    public function clearInfo($ip){

        $where = array(
            'vote_ip'=>$ip
        );
        return $this->delete($where);
    }
}