<?php

/**
 * Description:全民奇迹mu
 * Author: gradydong
 * Date: 2017/2/28
 * Time: 14:35
 * Copyright: Joyme.com
 */
if (!defined('IN'))
    die('bad request');
use Joyme\db\JoymeModel;

class ActionQmqjmuUsersModel extends JoymeModel
{
    public $fields = array();

    public $tableName = 'active_qmqjmu_users';

    public function __construct()
    {
        $this->db_config = array(
            'hostname' => 'rm-2ze39mi5sfts0i9f4.mysql.rds.aliyuncs.com',
            'username' => 'wikicr',
            'password' => 'bscIM5yK',
            'database' => 'hezuo'
        );
        parent::__construct();
    }
}