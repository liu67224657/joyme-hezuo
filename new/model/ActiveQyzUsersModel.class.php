<?php
if (!defined('IN'))
    die('bad request');
use Joyme\db\JoymeModel;

class ActiveQyzUsersModel extends JoymeModel
{
    public $fields = array();

    public $tableName = 'active_qyz_users';

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

    //增加用户信息
    public function addUserInfo($data){

        return $this->insert($data);
    }

    //用户信息列表
    public function allUserList($conditions,$total,$page=1,$pageage=10){

        $skip = intval(($page-1)*$pageage);

        if(!empty($conditions['sort']) && $conditions['sort'] == 1){
            $order = ' create_time desc ';
        }
        if(!empty($conditions['sort']) && $conditions['sort'] == 2){
            $order = ' vote_num desc ';
        }
        if(!empty($conditions['user_id'])){
            $where['user_id'] = $conditions['user_id'];
        }
        if(empty($conditions['status'])){
            $where['status'] = 2;
        }

        if($total){
            $count = $this->count($where);
        }else{
            $arr = $this->select('*',$where,$order,$pageage,$skip);
        }
        $result = $total?$count:$arr;

        return $result;
    }

    //更新投票数据
    public function updateUserVoteNum( $user_id ){

        return $this->excuteSql('update active_qyz_users set vote_num = vote_num+1 WHERE user_id = '.$user_id);
    }

    //根据ID查询用户
    public function getUserById( $where ){

        return $this->count($where);
    }

    //下架用户
    public function offUser( $where ){

        $data['status'] = 2;
        return $this->update($data,$where);
    }

    //更新投票数据
    public function updateUserVoteByAdmin( $user_id,$num ){

        return $this->excuteSql('update active_qyz_users set vote_num = vote_num+'.$num.' WHERE user_id = '.$user_id);

    }
}