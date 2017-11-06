<?php

/**
 * Description:微信相关CDK活动
 * Author: gradydong
 * Date: 2017/3/7
 * Time: 10:49
 * Copyright: Joyme.com
 */
if (!defined('IN'))
    die('bad request');
include_once(AROOT . 'model' . DS . 'WxCommonModel.class.php');

class ActiveCdkModel extends WxCommonModel
{
    public $fields = array();

    public $tableName = 'active_cdk';

    public function __construct()
    {
        parent::__construct();
    }
}