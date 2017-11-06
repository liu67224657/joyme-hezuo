<?php
/**
 * Created by PhpStorm.
 * User: kexuedong
 * Date: 2016/1/7
 * Time: 16:38
 */
if (!defined('IN'))
    die('bad request');

use Joyme\db\JoymeModel;

class ActivityCodeModel extends JoymeModel
{
    public $fields = array();

    public $tableName = 'activity_code';

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