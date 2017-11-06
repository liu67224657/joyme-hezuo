<?php
// 百度爱玩

use Joyme\db\JoymeModel;

class SougouHezuoArticleModel extends JoymeModel{
	
	public $tableName = 'sougou_hezuo';
	
	public function __construct(){
		$this->db_config = array(
            'hostname' => $GLOBALS['config']['db']['db_host'],
            'username' => $GLOBALS['config']['db']['db_user'],
            'password' => $GLOBALS['config']['db']['db_password'],
            'database' => $GLOBALS['config']['db']['db_name']
        );
        parent::__construct();
	}
	
	public function getData(){
		return $this->select('*', array('gamestatus'=>1), 'pubdate DESC');
	}
}

class SougouHezuoGameModel extends JoymeModel{
	
	public $tableName = 'sougou_hezuo_game';
	
	public function __construct(){
		$this->db_config = array(
            'hostname' => $GLOBALS['config']['db']['db_host'],
            'username' => $GLOBALS['config']['db']['db_user'],
            'password' => $GLOBALS['config']['db']['db_password'],
            'database' => $GLOBALS['config']['db']['db_name']
        );
        parent::__construct();
	}
	
	public function getData(){
		return $this->select('*', array('gamestatus'=>1), 'edittime DESC', null);
	}
}