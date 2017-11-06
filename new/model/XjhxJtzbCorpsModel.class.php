<?php

/**
 * Description:
 * Author: gradydong
 * Date: 2017/2/15
 * Time: 17:28
 * Copyright: Joyme.com
 */
if (!defined('IN'))
    die('bad request');
use Joyme\db\JoymeModel;

class XjhxJtzbCorpsModel extends JoymeModel
{
    public $fields = array();

    public $tableName = 'xjhx_jtzb_corps';

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

    //更新投票数据
    public function updateCorpsVoteNum( $id ){
        return $this->excuteSql('update xjhx_jtzb_corps set vote_num = vote_num+1 WHERE id = '.$id);
    }

    //更新投票数据
    public function updateCorpsVoteByAdmin( $id,$num ){

        return $this->excuteSql('update xjhx_jtzb_corps set vote_num = vote_num+'.$num.' WHERE id = '.$id);

    }
}