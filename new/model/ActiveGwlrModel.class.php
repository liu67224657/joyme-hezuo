<?php
if (!defined('IN'))
    die('bad request');
use Joyme\db\JoymeModel;

class ActiveGwlrModel extends JoymeModel
{
    public $fields = array();

    public $tableName = 'active_gwlr_gift';

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
}