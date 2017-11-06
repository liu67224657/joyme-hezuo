<?php

/**
 * Description:星际火线军团争霸投票model
 * Author: gradydong
 * Date: 2017/2/15
 * Time: 16:31
 * Copyright: Joyme.com
 */
if (!defined('IN'))
    die('bad request');
use Joyme\db\JoymeModel;

class XjhxJtzbVoteModel extends JoymeModel
{
    public $fields = array();

    public $tableName = 'xjhx_jtzb_vote';

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

    
}