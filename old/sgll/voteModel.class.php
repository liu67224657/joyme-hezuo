<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/6/24
 * Time: 9:46
 */
include_once("db/db.config.php");

use Joyme\db\JoymeModel;

class voteModel extends JoymeModel{

    //表字段
    public $fields = array(

    );
    //数据表名称
    public $tableName = 'sgll';
    /**
     * 构造函数
     */
    public function __construct() {

        global $config;

        $this->db_config = array(
            'hostname' => $config['host'],
            'username' => $config['username'],
            'password' => $config['password'],
            'database' => $config['db']
        );
        parent::__construct();
    }

    function selectSgllInfo(){

        $field = "*";
        return $this->select($field);
    }

    function updateSgllDate($num,$id){

        $data = array(
            'vote_num' => $num
        );
        $where = array(
            'id'=>$id
        );
        return $this->update($data,$where);
    }

    function addVoteNum($id){

        $result = $this->numChange('vote_num',array('id' =>array('eq',$id)),+1);
        return $result;
    }
}