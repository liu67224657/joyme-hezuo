<?php

/**
 * Description:微信活动用户相关信息
 * Author: gradydong
 * Date: 2017/3/7
 * Time: 11:58
 * Copyright: Joyme.com
 */
if (!defined('IN'))
    die('bad request');
include_once(AROOT . 'model' . DS . 'WxCommonModel.class.php');

class ActiveUsersModel extends WxCommonModel
{
    public $fields = array();

    public $tableName = 'active_users';

    public function __construct()
    {
        parent::__construct();
    }
}