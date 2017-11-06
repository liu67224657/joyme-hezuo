<?php

/**
 * Description:
 * Author: gradydong
 * Date: 2017/3/2
 * Time: 12:01
 * Copyright: Joyme.com
 */
if (!defined('IN'))
    die('bad request');
include_once(AROOT . 'model' . DS . 'WxCommonModel.class.php');

class ActiveGirldayUsersModel extends WxCommonModel
{
    public $fields = array();

    public $tableName = 'active_girlday_users';

    public function __construct()
    {
        parent::__construct();
    }

    //更新访问次数
    public function updateUsersVisitNum( $openid ){
        return $this->excuteSql('update active_girlday_users set visit_num = visit_num +1 WHERE openid = "'.$openid.'"');
    }

    //更新分享次数
    public function updateUsersShareNum( $openid ){
        return $this->excuteSql('update active_girlday_users set share_num = share_num +1 WHERE openid = "'.$openid.'"');
    }

    //更新分享成功次数
    public function updateUsersSharedNum( $openid ){
        return $this->excuteSql('update active_girlday_users set shared_num = shared_num +1 WHERE openid = "'.$openid.'"');
    }
}