<?php

/**
 * Description: wiki分类临时数据model
 * Author: gradydong
 * Date: 2017/3/23
 * Time: 15:34
 * Copyright: Joyme.com
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'model' . DS . 'CommonModel.class.php');

class WikiCategoryTempModel extends CommonModel
{
    public $fields = array();

    public $tableName = 'wiki_category_temp';

    public $wikikey = '';

    public $getchildstatus = false;
    
    public $cattemp_acatnames = array();
    public $cattemp_icons = array();
    public $cattemp_sorts = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function setWikikey($wikikey)
    {
        $this->wikikey = $wikikey;
    }

    public function insertcattemp($pname, $name, $sort)
    {
        $data = array(
            'wiki_key' => $this->wikikey,
            'wcat_name' => $name,
            'sort' => $sort
        );
        //父级name
        if ($pname == '') {
            $data['pname'] = 'root';
        } else {
            $data['pname'] = $pname;
        }
        //app->name
        if($this->cattemp_acatnames[$name]){
            $data['acat_name'] = $this->cattemp_acatnames[$name];
        }else{
            $data['acat_name'] = $name;
        }
        //分类icon
        if($this->cattemp_icons[$name]){
            $data['cat_icon'] = $this->cattemp_icons[$name];
        }
        return $this->insert($data);
    }

    function getchild($data, $pname = '')
    {
        if ($data) {
            foreach ($data as $k => $v) {
                if ($v['name']) {
                    $ret = $this->insertcattemp($pname, $v['name'], $v['sort']);
                    if (empty($ret)) {
                        $this->getchildstatus = true;
                        break;
                    }
                    if (isset($v['children']) && $v['children']) {
                        $this->getchild($v['children'], $v['name']);
                    }
                }
            }
            if ($this->getchildstatus) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    function delchild()
    {
        
    }
}