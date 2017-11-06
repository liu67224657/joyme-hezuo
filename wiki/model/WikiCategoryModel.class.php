<?php

/**
 * Description:
 * Author: gradydong
 * Date: 2017/3/23
 * Time: 15:33
 * Copyright: Joyme.com
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'model' . DS . 'CommonModel.class.php');

class WikiCategoryModel extends CommonModel
{
    public $fields = array();

    public $tableName = 'wiki_category';

    public function __construct()
    {
        parent::__construct();
    }

    //添加wiki分类删除临时表数据
    public function addcategoryfromtemp($wikikey)
    {
        $res = $this->excuteSql("insert into wiki_category (id,wiki_key,pname,wcat_name,acat_name,cat_icon,sort) (select id,wiki_key,pname,wcat_name,acat_name,cat_icon,sort from wiki_category_temp where wiki_key = '" . $wikikey . "')");
        if($res){
            $wikicattempmodel = new WikiCategoryTempModel();
            return $wikicattempmodel->delete(array(
                'wiki_key' => $wikikey
            ));
        }else{
            return false;
        }
    }

    public function getJwikiCategory($wikikey, $pname)
    {
        $catlist = $this->select('wcat_name,acat_name,cat_icon', array(
            'wiki_key' => $wikikey,
            'pname' => $pname
        ));
        $result = array();
        if ($catlist) {
            foreach ($catlist as $k => $v) {
                $result[$k]['picurl'] = $v['cat_icon'];
                $result[$k]['gamename'] = $v['acat_name'];
                $result[$k]['focusnum'] = "";
                $result[$k]['time'] = "";
                $url = "http://hezuo.joyme." . $GLOBALS['domain'] . "/wiki/index.php?c=wiki&a=aclist&wikikey=" . $wikikey . "&category=" . urlencode($v['wcat_name']);
                $result[$k]['ji'] = $url;
                $result[$k]['jt'] = "11";
            }
        }
        return $result;
    }
}