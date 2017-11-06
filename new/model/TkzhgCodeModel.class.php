<?php
/**
 * Created by PhpStorm.
 * User: kexuedong
 * Date: 2016/1/7
 * Time: 16:36
 */
if (!defined('IN'))
    die('bad request');

use Joyme\db\JoymeModel;

class TkzhgCodeModel extends JoymeModel
{
    public $fields = array();

    public $tableName = 'tkzhg_activity';

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

    //获取坦克指挥官用户信息列表
    public function getAllLists()
    {
        return $this->select('answer1,answer2,answer3,qq,email',array(),'id ASC','');
    }
}