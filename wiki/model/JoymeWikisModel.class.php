<?php

/**
 * Description:wiki管理后台wiki列表
 * Author: gradydong
 * Date: 2017/3/23
 * Time: 15:22
 * Copyright: Joyme.com
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'model' . DS . 'CommonModel.class.php');

class JoymeWikisModel extends CommonModel
{
    public $fields = array();

    public $tableName = 'joyme_wikis';

    public function __construct()
    {
        parent::__construct();
    }

    //把临时数据添加到wiki表
    public function addwikifromtemp($wikikey)
    {
        $res = $this->excuteSql("insert into joyme_wikis (wiki_key,game_id,wiki_name,wiki_icon,status) (select wiki_key,game_id,wiki_name,wiki_icon,status from joyme_wikis_temp where wiki_key = '" . $wikikey . "')");
        if($res){
            $jwikitempmodel = new JoymeWikisTempModel();
            return $jwikitempmodel->delete(array(
                'wiki_key' => $wikikey
            ));
        }else{
            return false;
        }
    }
}