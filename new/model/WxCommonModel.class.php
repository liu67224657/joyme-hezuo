<?php

/**
 * Description: 
 * Author: gradydong
 * Date: 2017/3/2
 * Time: 11:58
 * Copyright: Joyme.com
 */
if (!defined('IN'))
    die('bad request');
use Joyme\db\JoymeModel;

class WxCommonModel extends JoymeModel
{
    public $fields = array();

    public $tableName = '';

    public function __construct()
    {
        if($GLOBALS['domain'] == 'alpha'){
            $this->db_config = array(
                'hostname' => $GLOBALS['config']['db']['db_host'],
                'username' => $GLOBALS['config']['db']['db_user'],
                'password' => $GLOBALS['config']['db']['db_password'],
                'database' => $GLOBALS['config']['db']['db_name']
            );
        }else{
            $this->db_config = array(
                'hostname' => 'rm-2ze39mi5sfts0i9f4.mysql.rds.aliyuncs.com',
                'username' => 'wikicr',
                'password' => 'bscIM5yK',
                'database' => 'hezuo'
            );
        }
        parent::__construct();
    }
}