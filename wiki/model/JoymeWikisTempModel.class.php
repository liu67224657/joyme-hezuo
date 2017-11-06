<?php

/**
 * Description:wiki管理后台临时wiki列表
 * Author: gradydong
 * Date: 2017/4/5
 * Time: 11:46
 * Copyright: Joyme.com
 */

if (!defined('IN')) die('bad request');
include_once(AROOT . 'model' . DS . 'CommonModel.class.php');

class JoymeWikisTempModel extends CommonModel
{
    public $fields = array();

    public $tableName = 'joyme_wikis_temp';

    public function __construct()
    {
        parent::__construct();
    }
}