<?php
if (!defined('IN'))
    die('bad request');
use Joyme\db\JoymeModel;

class ActiveQmqjVoteModel extends JoymeModel
{
    public $fields = array();

    public $tableName = 'active_qmqj_vote';

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

    //����ͶƱ��Ϣ
    public function addVoteInfo($data){

        return $this->insert($data);
    }


    //�鿴����ͶƱ����
    public function getCountNumber($where){

       return  $this->count($where);
    }
}