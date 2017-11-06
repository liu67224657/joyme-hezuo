<?php

/**
 * Description:
 * Author: gradydong
 * Date: 2017/2/8
 * Time: 17:27
 * Copyright: Joyme.com
 */
if (!defined('IN'))
    die('bad request');
use Joyme\db\JoymeModel;

class QlryUsersModel extends JoymeModel
{
    public $fields = array();

    public $tableName = 'qlry_users';

    public function __construct()
    {
        /*$this->db_config = array(
            'hostname' => $GLOBALS['config']['db']['db_host'],
            'username' => $GLOBALS['config']['db']['db_user'],
            'password' => $GLOBALS['config']['db']['db_password'],
            'database' => $GLOBALS['config']['db']['db_name']
        );*/
        $this->db_config = array(
            'hostname' => 'rm-2ze39mi5sfts0i9f4.mysql.rds.aliyuncs.com',
            'username' => 'wikicr',
            'password' => 'bscIM5yK',
            'database' => 'hezuo'
        );
        parent::__construct();
    }

    //更新投票数据
    public function updateUserVoteNum( $user_id ){

        return $this->excuteSql('update qlry_users set vote_num = vote_num+1 WHERE user_id = '.$user_id);
    }

    //更新投票数据
    public function updateUserVoteByAdmin( $user_id,$num ){

        return $this->excuteSql('update qlry_users set vote_num = vote_num+'.$num.' WHERE user_id = '.$user_id);

    }
}