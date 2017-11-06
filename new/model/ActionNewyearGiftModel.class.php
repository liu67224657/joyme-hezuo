<?php

/**
 * Description: 新年签到和抽奖
 * Author: gradydong
 * Date: 2017/1/19
 * Time: 15:51
 * Copyright: Joyme.com
 */
if (!defined('IN'))
    die('bad request');
use Joyme\db\JoymeModel;

class ActionNewyearGiftModel extends JoymeModel
{
    public $fields = array();

    public $tableName = 'active_newyear_gift';

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