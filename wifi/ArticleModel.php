<?php

class ArticleModel{
	
	private $link;
	static private $_instance;
	
	private function __construct(){
		$host = $GLOBALS['config']['db']['db_host'];
		$username = $GLOBALS['config']['db']['db_user'];
		$password = $GLOBALS['config']['db']['db_password'];
		$dbname = $GLOBALS['config']['db']['db_name'];
		$this->link = mysqli_connect($host, $username, $password) or $this->err('服务器连接失败');
		mysqli_select_db($this->link , $dbname) or $this->err('数据库连接失败');
		mysqli_set_charset($this->link, 'utf8');
		return $this->link;
	}
	
	public static function getDBr(){
		if(FALSE == (self::$_instance instanceof self)){
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	public function query($sql){
		return mysqli_query($this->link, $sql);
	}
	
	public function getRows($sql){
		$rows = array();
		$result = $this->query($sql);
		if( !$result ) return $rows;
        while($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
	}
	
	public function getRow($sql){
		$result = $this->query($sql);
		if( !$result ) return array();
        return mysqli_fetch_assoc($result);
	}
	
	protected function err($msg) {
		err($msg);exit;
	}
	
}