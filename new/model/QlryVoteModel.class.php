<?php

/**
 * Description: 权利荣耀投票
 * Author: gradydong
 * Date: 2017/2/8
 * Time: 17:25
 * Copyright: Joyme.com
 */
if (!defined('IN'))
    die('bad request');
use Joyme\db\JoymeModel;

class QlryVoteModel extends JoymeModel
{
    public $fields = array();

    public $tableName = 'qlry_vote';

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

    public function addVoteInfo($data){

        return $this->insert($data);
    }

    public function getCountNumber($where){

        return  $this->count($where);
    }
}