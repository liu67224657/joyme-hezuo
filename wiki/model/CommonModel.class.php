<?php

/**
 * Description:公共model类
 * Author: gradydong
 * Date: 2017/3/21
 * Time: 12:02
 * Copyright: Joyme.com
 */
if (!defined('IN'))
    die('bad request');
use Joyme\db\JoymeModel;

class CommonModel extends JoymeModel
{
    public $fields = array();

    public $tableName = '';

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
}