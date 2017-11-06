<?php
// 百度爱玩

use Joyme\db\JoymeModel;

class BaiduHezuoModel extends JoymeModel{
	
	public $tableName = 'baidu_hezuo';
	
	public function __construct(){
		$this->db_config = array(
            'hostname' => $GLOBALS['config']['db']['db_host'],
            'username' => $GLOBALS['config']['db']['db_user'],
            'password' => $GLOBALS['config']['db']['db_password'],
            'database' => $GLOBALS['config']['db']['db_name']
        );
        parent::__construct();
	}
	
	public function getData($where){
		return $this->select('*', $where, 'pubdate DESC', null);
	}
}