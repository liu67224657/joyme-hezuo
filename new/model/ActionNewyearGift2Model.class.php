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

class ActionNewyearGift2Model extends JoymeModel
{
    public $fields = array();

    public $tableName = 'active_newyear_gift_2';

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