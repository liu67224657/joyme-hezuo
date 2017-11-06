<?php
// 斗鱼直播

use Joyme\db\JoymeModel;

class DouyuHezuoModel extends JoymeModel{
	
	public $tableName = 'zhibo_douyu';
	
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
		return $this->select('*', $where, 'pubdate DESC');
	}
}