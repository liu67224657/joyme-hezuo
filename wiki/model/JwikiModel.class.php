<?php

/**
 * Description:
 * Author: gradydong
 * Date: 2017/3/23
 * Time: 11:42
 * Copyright: Joyme.com
 */
if (!defined('IN'))
    die('bad request');
use Joyme\db\JoymeModel;

class JwikiModel extends JoymeModel
{
    public $fields = array();

    public $tableName = '';

    public $wikikey = '';

    public function __construct($wikikey)
    {
        global $GLOBALS;
        $this->wikikey = $wikikey;
        if ($GLOBALS['domain'] == 'com') {
            $this->db_config = array(
                'hostname' => 'rm-2zem35fsj37eqzu3p.mysql.rds.aliyuncs.com',
                'username' => 'wikicr',
                'password' => 'bscIM5yK',
                'database' => $wikikey . 'wiki'
            );
        } elseif ($GLOBALS['domain'] == 'beta') {
            $this->db_config = array(
                'hostname' => '10.171.101.30',
                'username' => 'wikiuser',
//                'password' => 'I87DCXp6',
                'password' => '123456',
                'database' => $wikikey . 'wiki'
            );
        } else {
            /*$this->db_config = array(
                'hostname' => '172.16.75.32',
                'username' => 'root',
                'password' => '123456',
                'database' => $wikikey . 'wiki'
            );*/
            /*$this->db_config = array(
                'hostname' => '172.16.75.143',
                'username' => 'wikiuser',
                'password' => 'I87DCXp6',
                'database' => $wikikey . 'wiki'
            );*/
            $this->db_config = array(
                'hostname' => '172.16.75.143',
                'username' => 'dongkexue',
                'password' => 'qnlvdAeQ',
                'database' => $wikikey . 'wiki'
            );
        }
        parent::__construct();
    }

    //设置查询数据库表名
    public function setTableName($tablename)
    {
        $this->tableName = $tablename;
    }

    //获取分类
    public function getCategorys()
    {
        return $this->excuteSql('SELECT  cat_title,cat_pages,cat_subcats  FROM `category`  FORCE INDEX (cat_title) ORDER BY cat_title LIMIT 1000');
    }

    //获取所有子分类
    public function getSubCategorys()
    {
        return $this->excuteSql("SELECT  cl_to as f_title,page_title as s_title FROM `page` INNER JOIN `categorylinks` FORCE INDEX (cl_sortkey) ON ((cl_from = page_id)) WHERE cl_type = 'subcat'  ORDER BY cl_first_letter LIMIT 500");
    }

    //获取分类文件列表
    public function getArticleByCat($category, $offset, $limit)
    {
        return $this->excuteSql("SELECT p.page_title FROM `page` AS p INNER JOIN `categorylinks` AS c FORCE INDEX (cl_sortkey) ON ((c.cl_from = p.page_id))  RIGHT JOIN `page_addons` AS pa ON (p.page_id = pa.page_id)  WHERE c.cl_to = '" . $category . "' AND c.cl_type = 'page' ORDER BY pa.pa_timestamp DESC,pa.page_id DESC LIMIT " . $offset . "," . $limit);
    }

    public function getArticleLists($category, $cat_icon, $offset, $limit)
    {
        $result = array();
        $alists = $this->getArticleByCat($category, $offset, $limit);
        if ($alists) {
            foreach ($alists as $k => $v) {
//                $result[$k]['picurl'] = $cat_icon;
                $result[$k]['picurl'] = "http://static.joyme.com/mobile/wikiapp/article_icon.png";
                $result[$k]['gamename'] = $v['page_title'];
                $result[$k]['focusnum'] = "";
                $result[$k]['time'] = "";
                $url = "http://wiki.joyme." . $GLOBALS['domain'] . "/" . $this->wikikey . "/" . $v['page_title'] . "?useskin=MediaWikiBootstrap2";
                $result[$k]['ji'] = urlencode($url);
                $result[$k]['jt'] = "-2";
            }
        }
        return $result;
    }

    public function getArticleNextExsit($category, $offset, $limit)
    {
        return $this->excuteSql("SELECT p.page_id FROM `page` AS p INNER JOIN `categorylinks` AS c FORCE INDEX (cl_sortkey) ON ((c.cl_from = p.page_id))  RIGHT JOIN `page_addons` AS pa ON (p.page_id = pa.page_id)  WHERE c.cl_to = '" . $category . "' AND c.cl_type = 'page' ORDER BY pa.pa_timestamp DESC,pa.page_id DESC LIMIT " . $offset . "," . $limit);
    }

    //获取wikiname
    public function getWikiInfo()
    {
        $lists = $this->excuteSql("select site_name from site_info where sid=1");
        if ($lists) {
            return $lists[0];
        } else {
            return array();
        }
    }


    //根据wikikey获取指定wiki的分类
    public function getwikicategory()
    {
        $allcats = array();
        $fcats = $this->getCategorys();
        if ($fcats) {
            $afcats = array();
            foreach ($fcats as $k => $fcat) {
                $afcats[$fcat['cat_title']]['cat_title'] = $fcat['cat_title'];
                $afcats[$fcat['cat_title']]['cat_pages'] = $fcat['cat_pages'];
                $afcats[$fcat['cat_title']]['cat_subcats'] = $fcat['cat_subcats'];
            }
            $scats = $this->getSubCategorys();
            if ($scats) {
                $f_titles = array();
                $s_titles = array();
                foreach ($scats as $k => $scat) {
                    $s_titles[$scat['f_title']][] = $scat['s_title'];
                    $f_titles[] = $scat['f_title'];
                }
                if ($s_titles) {
                    $f_titles = array_keys($s_titles);
                }
            } else {
                $f_titles = array();
                $s_titles = array();
            }
            $i = 0;
            $allcats_1 = array();
            $allcats_2 = array();
            foreach ($fcats as $k => $fcat) {
                //去掉没有页面的分类
                if ($fcat['cat_pages']) {
                    if (in_array($fcat['cat_title'], $f_titles)) {
                        if ($s_titles[$fcat['cat_title']]) {
                            foreach ($s_titles[$fcat['cat_title']] as $sk => $sv) {
                                if ($afcats[$sv]) {
                                    $s_cat = $afcats[$sv];
                                    if ($s_cat['cat_pages'] - $s_cat['cat_subcats']) {
                                        $allcats_2[$s_cat['cat_title']]['pname'] = $fcat['cat_title'];
                                        $allcats_2[$s_cat['cat_title']]['name'] = $s_cat['cat_title'];
                                    }
                                }
                                $i++;
                            }
                        }
                    }
                    $allcats_1[$i]['pname'] = "";
                    $allcats_1[$i]['name'] = $fcat['cat_title'];
                    $allcats_1[$i]['pagenums'] = ($fcat['cat_pages'] - $fcat['cat_subcats']);
                }
                $i++;
            }
            $catkeys = array_keys($allcats_2);
            $pcatkeys = array_column($allcats_2, 'pname');
            $allindex = 0;
            foreach ($allcats_1 as $ak => $allcat) {
                if (in_array($allcat['name'], $catkeys)) {
                    $allcats[$allindex]['pname'] = $allcats_2[$allcat['name']]['pname'];
                    $allcats[$allindex]['name'] = $allcat['name'];
                    $allcats[$allindex]['pagenums'] = $allcat['pagenums'];
                } else {
                    if ($allcat['pagenums'] > 0 || in_array($allcat['name'], $pcatkeys)) {
                        $allcats[$allindex]['pname'] = $allcat['pname'];
                        $allcats[$allindex]['name'] = $allcat['name'];
                        $allcats[$allindex]['pagenums'] = $allcat['pagenums'];
                    }
                }
                $allindex++;
            }
        }
        return $allcats;
    }

}