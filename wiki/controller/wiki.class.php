<?php
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'app.class.php');
include_once(AROOT . 'controller' . DS . 'tree.class.php');

use Joyme\core\Request;
use Joyme\qiniu\Qiniu_Utils;
use Joyme\net\Curl;
use Joyme\core\Log;

class wiki extends app
{

    //页面展示
    public function wlist()
    {
        global $GLOBALS;
        $conditions = $where = array();
        //wiki名称
        $wikiname = Request::getParam('wikiname', '');
        if ($wikiname) {
            $where['wiki_name'] = array('like', '%' . $wikiname . '%');
            $conditions['wiki_name'] = $wikiname;
        }
        //wikikey
        $wikikey = Request::getParam('wikikey', '');
        if ($wikikey) {
            $where['wiki_key'] = $wikikey;
            $conditions['wiki_key'] = $wikikey;
        }

        $joymewikimodel = new JoymeWikisModel();
        $total = $joymewikimodel->count($where);
        $psize = 20; //每页显示条数
        $pno = Request::get('pb_page', 1);
        $skip = 0;
        if ($pno) {
            $skip = (intval($pno) - 1) * $psize;
        }
        $lists = $joymewikimodel->select("*", $where, 'id DESC', $psize, $skip);
        if ($lists) {
            //获取游戏名称
            $gameids = array_column($lists, 'game_id');
            $gamenames = $this->querygame($gameids);
            if ($gamenames) {
                foreach ($lists as $k => $list) {
                    if ($gamenames[$list['game_id']]) {
                        $lists[$k]['game_name'] = $gamenames[$list['game_id']];
                    }
                }
            }
        }
        //分页
        $page = new pageModel();
        $page->mainPage(array('total' => $total, 'perpage' => $psize, 'nowindex' => $pno, 'pagebarnum' => 10));
        $phtml = $page->show(2, $conditions);
        $data = array(
            'wikiname' => $wikiname,
            'wikikey' => $wikikey,
            'total' => $total,
            'list' => $lists,
            'phtml' => $phtml,
            'pno' => $pno
        );
        render($data, 'web', 'admin/wikilist');
    }


    public function addwiki()
    {
        if ($_POST) {
            header('Content-Type:text/html;charset=utf-8');
            $wikikey = Request::getParam('wikikey');
            $wikiicon = Request::getParam('litpic');
            $gameid = Request::getParam('gameid');
            if ($wikikey && $wikiicon && $gameid) {
                //添加wiki
                $jwikimodel = new JoymeWikisModel();
                $jwikitempmodel = new JoymeWikisTempModel();
                $jwikitemplist = $jwikitempmodel->selectRow('game_id,wiki_name,wiki_icon', array(
                    'wiki_key' => $wikikey
                ));
                if ($jwikitemplist) {
                    $jwikiret = $jwikimodel->insert(array(
                        'game_id' => (int)$gameid,
                        'wiki_name' => $jwikitemplist['wiki_name'],
                        'wiki_icon' => $wikiicon,
                        'wiki_key' => $wikikey,
                        'status' => 1
                    ));
                    if ($jwikiret) {
                        $jwikitempmodel->delete(array(
                            'wiki_key' => $wikikey
                        ));
                        //添加wiki分类表
                        $wikicattempmodel = new WikiCategoryTempModel();
                        $cattemplists = $wikicattempmodel->select('*', array('wiki_key' => $wikikey), '', '');
                        if ($cattemplists) {
                            $redis = $this->contentRedis();
                            foreach ($cattemplists as $k => $cattemplist) {
                                if ($cattemplist['pname'] == 'root') {
                                    $prefix = "jwikiadmin|" . $wikikey . "|category|root";
                                    $valfix = "jwikiadmin|" . $wikikey . "|category|root|" . $cattemplist['wcat_name'];
                                } else {
                                    $prefix = "jwikiadmin|" . $wikikey . "|category|" . $cattemplist['pname'];
                                    $valfix = "jwikiadmin|" . $wikikey . "|category|" . $cattemplist['pname'] . "|" . $cattemplist['wcat_name'];
                                }
                                $redis->zAdd($prefix, $cattemplist['sort'], $valfix);
                                $redis->set($valfix, json_encode($cattemplist));
                            }
                        }
                        $wikicatmodel = new WikiCategoryModel();
                        $wikicatret = $wikicatmodel->addcategoryfromtemp($wikikey);
                        if ($wikicatret) {
                            echo '操作成功 <a href="/wiki/index.php?c=wiki&a=wlist">返回列表</a>';
                        } else {
                            echo '操作失败 <a href="/wiki/index.php?c=wiki&a=wlist">返回列表</a>';
                        }
                    } else {
                        echo '操作失败 <a href="/wiki/index.php?c=wiki&a=wlist">返回列表</a>';
                    }
                }
            } else {
                echo '参数不能为空 <a href="/wiki/index.php?c=wiki&a=addwiki&wikikey=' . $wikikey . '&wikiicon=' . $wikiicon . '">返回</a>';
            }
        } else {
            $uptoken = $this->getUptoken();
            $cattemplists = array();
            $wikitemplist = array();
            $wikikey = Request::getParam('wikikey');
            $wikiicon = Request::getParam('wikiicon');
            if ($wikikey && empty($wikiicon)) {
                $jwikicattemp = new WikiCategoryTempModel();
                $cattemplists = $jwikicattemp->select('*', array(
                    'wiki_key' => $wikikey
                ), 'sort ASC', '');
                $jwikitempmodel = new JoymeWikisTempModel();
                $wikitemplist = $jwikitempmodel->selectRow('wiki_key,wiki_name,wiki_icon,game_id', array(
                    'wiki_key' => $wikikey
                ));
                if ($cattemplists) {
                    $tree = new Tree();
                    $cattemplists = $tree->gettreelists($cattemplists);
                }
                $wikiicon = $wikitemplist['wiki_icon'];
                $gamename = $this->querygame($wikitemplist['game_id']);
                if ($gamename) {
                    $gamename = $gamename[$wikitemplist['game_id']];
                }
            }
            $data = array(
                'uptoken' => $uptoken,
                'wikikey' => $wikikey,
                'wikiicon' => $wikiicon,
                'gamename' => $gamename,
                'cattemplists' => $cattemplists,
                'wikitemplist' => $wikitemplist
            );
            render($data, 'web', 'admin/addwiki');
        }
    }

    public function editwiki()
    {
        global $GLOBALS;
        if ($_POST) {
            header('Content-Type:text/html;charset=utf-8');
            $wikikey = Request::getParam('wikikey');
            $wikiicon = Request::getParam('litpic');
            $gameid = Request::getParam('gameid');
            if ($wikikey && $wikiicon) {
                //更新wiki列表
                $jwikimodel = new JoymeWikisModel();
                $jwikimodel->update(array(
                    'wiki_icon' => $wikiicon
                ), array(
                    'wiki_key' => $wikikey
                ));
                $this->updateheadicon($wikikey, $gameid, $wikiicon);
                //删除wiki临时表
                $jwikitempmodel = new JoymeWikisTempModel();
                $jwikitempmodel->delete(array(
                    'wiki_key' => $wikikey
                ));
                //wiki分类临时数据
                $wikicattempmodel = new WikiCategoryTempModel();
                $cattemplists = $wikicattempmodel->select('*', array('wiki_key' => $wikikey), 'sort ASC', '');
                if ($cattemplists) {
                    $this->delredisdata($wikikey);
                    $redis = $this->contentRedis();
                    $cattempwikinames = array_column($cattemplists, 'wcat_name');
                    //wiki分类表
                    $wikicatmodel = new WikiCategoryModel();
                    $catlists = $wikicatmodel->select('*', array('wiki_key' => $wikikey), 'sort ASC', '');
                    if ($catlists) {
                        $catwikinames = array_column($catlists, 'wcat_name');
                        $caticons = array_column($catlists, 'cat_icon', 'wcat_name');
                        //删除没有选中的分类
                        foreach ($catlists as $k => $catlist) {
                            if (!in_array($catlist['wcat_name'], $cattempwikinames)) {
                                $wikicatmodel->delete(array(
                                    'id' => $catlist['id']
                                ));
                            }
                        }
                    } else {
                        $catwikinames = array();
                        $caticons = array();
                    }
                    foreach ($cattemplists as $k => $cattemplist) {
                        if ($cattemplist['pname'] == 'root') {
                            $prefix = "jwikiadmin|" . $wikikey . "|category|root";
                            $valfix = "jwikiadmin|" . $wikikey . "|category|root|" . $cattemplist['wcat_name'];
                        } else {
                            $prefix = "jwikiadmin|" . $wikikey . "|category|" . $cattemplist['pname'];
                            $valfix = "jwikiadmin|" . $wikikey . "|category|" . $cattemplist['pname'] . "|" . $cattemplist['wcat_name'];
                        }
                        if (in_array($cattemplist['wcat_name'], $catwikinames)) {
                            $data = array(
                                'pname' => $cattemplist['pname'],
                                'acat_name' => $cattemplist['acat_name'],
                                'sort' => $cattemplist['sort']
                            );
                            if (!empty($cattemplist['cat_icon'])) {
                                $data['cat_icon'] = $cattemplist['cat_icon'];
                            } else {
                                if ($caticons[$cattemplist['wcat_name']]) {
                                    $cattemplist['cat_icon'] = $caticons[$cattemplist['wcat_name']];
                                }
                            }
                            $wikicatmodel->update($data, array(
                                'wiki_key' => $wikikey,
                                'wcat_name' => $cattemplist['wcat_name']
                            ));
                        } else {
                            $wikicatmodel->insert(array(
                                'wiki_key' => $cattemplist['wiki_key'],
                                'pname' => $cattemplist['pname'],
                                'wcat_name' => $cattemplist['wcat_name'],
                                'acat_name' => $cattemplist['acat_name'],
                                'cat_icon' => $cattemplist['cat_icon'],
                                'sort' => $cattemplist['sort']
                            ));
                        }
                        $redis->zAdd($prefix, $cattemplist['sort'], $valfix);
                        $redis->set($valfix, json_encode($cattemplist));
                    }
                    $wikicattempmodel = new WikiCategoryTempModel();
                    $wikicattempret = $wikicattempmodel->delete(array(
                        'wiki_key' => $wikikey
                    ));
                    if ($wikicattempret) {
                        echo '操作成功 <a href="/wiki/index.php?c=wiki&a=wlist">返回列表</a>';
                    } else {
                        echo '操作失败 <a href="/wiki/index.php?c=wiki&a=wlist">返回列表</a>';
                    }
                } else {
                    echo '操作成功 <a href="/wiki/index.php?c=wiki&a=wlist">返回列表</a>';
                }
            } else {
                echo '参数错误 <a href="/wiki/index.php?c=wiki&a=wlist">返回列表</a>';
            }
        } else {
            $data = array();
            $id = (int)Request::getParam('id');
            if (!empty($id) && is_numeric($id)) {
                $joymewikimodel = new JoymeWikisModel();
                $joymewikislist = $joymewikimodel->selectRow("wiki_key,wiki_name,wiki_icon,game_id", array(
                    'id' => $id
                ));
                if ($joymewikislist) {
                    $gamename = $this->querygame($joymewikislist['game_id']);
                    if ($gamename) {
                        $gamename = $gamename[$joymewikislist['game_id']];
                    }
                    $cattype = Request::getParam('cattype');
                    $wikicategorymodel = new WikiCategoryModel();
                    $wikicategorylists = $wikicategorymodel->select("id,pname,wcat_name,acat_name,cat_icon,sort", array(
                        "wiki_key" => $joymewikislist['wiki_key']
                    ), "sort ASC", "");
                    if ($cattype == 'temp') {
                        $acat_names = array_column($wikicategorylists, 'acat_name', 'wcat_name');
                        $cat_icons = array_column($wikicategorylists, 'cat_icon', 'wcat_name');
                        $wikicattempmodel = new WikiCategoryTempModel();
                        $wikicattemplists = $wikicattempmodel->select("id,pname,wcat_name,acat_name,cat_icon,sort", array(
                            "wiki_key" => $joymewikislist['wiki_key']
                        ), "sort ASC", "");
                        if ($wikicattemplists) {
                            foreach ($wikicattemplists as $k => $v) {
                                if ($acat_names[$v['wcat_name']]) {
                                    $wikicattemplists[$k]['acat_name'] = $acat_names[$v['wcat_name']];
                                }
                                if ($cat_icons[$v['wcat_name']]) {
                                    $wikicattemplists[$k]['cat_icon'] = $cat_icons[$v['wcat_name']];
                                }
                                $wikicattemplists[$k]['cattype'] = 'temp';
                            }
                        }
                        $catlists = $wikicattemplists;
                        $joymewikitempmodel = new JoymeWikisTempModel();
                        $joymewikistemplist = $joymewikitempmodel->selectRow("wiki_icon", array(
                            "wiki_key" => $joymewikislist['wiki_key']
                        ));
                        if($joymewikistemplist){
                            $wikiicon = $joymewikistemplist['wiki_icon'];
                        }else{
                            $wikiicon = $joymewikislist['wiki_icon'];
                        }
                    } else {
                        $catlists = $wikicategorylists;
                        $wikiicon = $joymewikislist['wiki_icon'];
                    }
                    $tree = new Tree();
                    $catlists = $tree->gettreelists($catlists);
                    $uptoken = $this->getUptoken();
                    $data = array(
                        'id' => $id,
                        'uptoken' => $uptoken,
                        'gamename' => $gamename,
                        'gameid' => $joymewikislist['game_id'],
                        'catlists' => $catlists,
                        'wikilist' => $joymewikislist,
                        'wikiicon' => $wikiicon
                    );
                }
            }
            render($data, 'web', 'admin/editwiki');
        }
    }


    ########################################################################
    ###############################后台ajax接口开始##########################
    ########################################################################

    ###############################wiki列表页###############################

    //修改wiki状态
    public function editwstatus()
    {
        $id = Request::post('id');
        if ($id && is_numeric($id)) {
            $status = (int)Request::post('status');
            $joymewikimodel = new JoymeWikisModel();
            $list = $joymewikimodel->selectRow('wiki_key,game_id', array(
                'id' => $id
            ));
            if ($list) {
                $res = $joymewikimodel->update(array(
                    'status' => $status
                ), array(
                    'id' => $id
                ));
                if ($res) {
                    $gameres = $this->updategamestatus($list['wiki_key'], $list['game_id'], $status);
                    if ($gameres) {
                        $this->echojson(array(
                            'rs' => '1',
                            'msg' => '修改成功',
                        ));
                    } else {
                        $this->echojson(array(
                            'rs' => '0',
                            'msg' => '修改游戏库游戏状态失败'
                        ));
                    }
                } else {
                    $this->echojson(array(
                        'rs' => '0',
                        'msg' => '修改失败',
                    ));
                }
            } else {
                $this->echojson(array(
                    'rs' => '0',
                    'msg' => 'wiki不存在',
                ));
            }
        } else {
            $this->echojson(array(
                'rs' => '0',
                'msg' => '参数错误',
            ));
        }
    }


    #######################################################################
    ###############################添加wiki################################
    #######################################################################

    //添加wiki时，检查wikikey
    public function checkwikikey()
    {
        global $GLOBALS;
        $wikikey = Request::getParam('wikikey');
        if (!empty($wikikey)) {
            $joymewikismodel = new JoymeWikisModel();
            $wikilist = $joymewikismodel->selectRow('id', array('wiki_key' => $wikikey));
            if (empty($wikilist)) {
                $url = 'http://wikiservice.joyme.' . $GLOBALS['domain'] . '/api/wiki/game/getgamebykey';
                $curl = new Curl();
                $res = $curl->Get($url, array(
                    'wikikey' => $wikikey
                ));
                $res = json_decode($res, true);
                if ($res['rs'] == 1) {
                    $result = array();
                    if (isset($res['result'])
                        && $res['result']
                    ) {
                        foreach ($res['result'] as $k => $v) {
                            $result['gameid'] = $k;
                            $result['gamename'] = $v;
                        }
                    }
                    $jwikimodel = new JwikiModel($wikikey);
                    $wikiinfo = $jwikimodel->getWikiInfo();
                    if ($wikiinfo) {
                        $result['wikiname'] = $wikiinfo['site_name'];
                        $this->echojson(array(
                            'rs' => '1',
                            'msg' => 'success',
                            'result' => $result
                        ));
                    } else {
                        $this->echojson(array(
                            'rs' => '0',
                            'msg' => '该wiki没有创建'
                        ));
                    }
                } elseif ($res['rs'] == '-60001') {
                    $this->echojson(array(
                        'rs' => '2',
                        'msg' => '该wikiKey没有关联游戏'
                    ));
                } elseif ($res['rs'] == '-1000') {
                    $this->echojson(array(
                        'rs' => '0',
                        'msg' => '系统错误'
                    ));
                } elseif ($res['rs'] == '-1001') {
                    $this->echojson(array(
                        'rs' => '0',
                        'msg' => 'wikikey不能为空'
                    ));
                } else {
                    $this->echojson(array(
                        'rs' => '0',
                        'msg' => '系统错误'
                    ));
                }
            } else {
                $this->echojson(array(
                    'rs' => '0',
                    'msg' => 'wiki已存在'
                ));
            }
        } else {
            $this->echojson(array(
                'rs' => '0',
                'msg' => '参数不对'
            ));
        }
    }


    //根据wikikey获取分类
    public function getcatbywikikey()
    {
        $wikikey = Request::getParam('wikikey');
        $type = Request::getParam('type');
        if (!empty($wikikey)) {
            $jwiki = new JwikiModel($wikikey);
            $lists = $jwiki->getwikicategory();
            if ($lists) {
                $tree = new Tree();
                if ($type == 'editcat') {
                    $wikicattempmodel = new WikiCategoryTempModel();
                    $wikicattemplists = $wikicattempmodel->select('wcat_name,sort', array('wiki_key' => $wikikey), '', '');
                    if ($wikicattemplists) {
                        $tree->exsitdata = array_column($wikicattemplists, 'wcat_name');
                        $tree->sortdata = array_column($wikicattemplists, 'sort', 'wcat_name');
                    } else {
                        $wikicatmodel = new WikiCategoryModel();
                        $wikicatlists = $wikicatmodel->select('wcat_name,sort', array('wiki_key' => $wikikey), '', '');
                        if ($wikicatlists) {
                            $tree->exsitdata = array_column($wikicatlists, 'wcat_name');
                            $tree->sortdata = array_column($wikicatlists, 'sort', 'wcat_name');
                        }
                    }
                    $lists = $tree->getselectedtree($lists);
                } else {
                    $wikicattempmodel = new WikiCategoryTempModel();
                    $wikicattemplists = $wikicattempmodel->select('wcat_name,sort', array('wiki_key' => $wikikey), '', '');
                    if ($wikicattemplists) {
                        $tree->exsitdata = array_column($wikicattemplists, 'wcat_name');
                        $tree->sortdata = array_column($wikicattemplists, 'sort', 'wcat_name');
                    }
                    $lists = $tree->getselectedtree($lists);
                }
                $this->echojson(array(
                    'rs' => '1',
                    'msg' => $lists
                ));
            } else {
                $this->echojson(array(
                    'rs' => '1',
                    'msg' => array()
                ));
            }
        } else {
            $this->echojson(array(
                'rs' => '0',
                'msg' => '参数不对',
                'result' => array()
            ));
        }
    }

    //添加分类临时数据
    public function addcattemp()
    {
        $wikikey = Request::getParam('wikikey');
        $wikiicon = Request::getParam('wikiicon');
        $wikiname = Request::getParam('wikiname');
        $gameid = (int)Request::getParam('gameid');
        $data = Request::getParam('data');
        if ($wikikey && $data) {
            $jwikitempmodel = new JoymeWikisTempModel();
            //删除未保存的临时数据
            $jwikitempmodel->delete(array(
                'wiki_key' => $wikikey
            ));
            $jwikicattemp = new WikiCategoryTempModel();
            $cattemps = $jwikicattemp->select('wcat_name,acat_name,cat_icon,sort', array(
                'wiki_key' => $wikikey
            ));
            if ($cattemps) {
                $jwikicattemp->cattemp_acatnames = array_column($cattemps, 'acat_name', 'wcat_name');
                $jwikicattemp->cattemp_icons = array_column($cattemps, 'cat_icon', 'wcat_name');
                $jwikicattemp->cattemp_sorts = array_column($cattemps, 'sort', 'wcat_name');
            }
            //删除未保存的临时数据
            $jwikicattemp->delete(array(
                'wiki_key' => $wikikey
            ));
            $jwikicattemp->setWikikey($wikikey);
            $ret = $jwikicattemp->getchild($data);
            if ($ret === false) {
                $this->echojson(array(
                    'rs' => '0',
                    'msg' => '添加失败',
                ));
            } else {
                $jwikitempmodel->insert(array(
                    'wiki_key' => $wikikey,
                    'wiki_name' => $wikiname,
                    'game_id' => $gameid,
                    'wiki_icon' => $wikiicon
                ));
                $this->echojson(array(
                    'rs' => '1',
                    'msg' => 'success',
                ));
            }
        } else {
            $this->echojson(array(
                'rs' => '0',
                'msg' => '参数不正确',
            ));
        }
    }

    //删除临时数据
    public function delcattemp()
    {
        $wikikey = Request::getParam('wikikey');
        if ($wikikey) {
            $flag = true;
            $jwikitempmodel = new JoymeWikisTempModel();
            $jwikitempcount = $jwikitempmodel->count(array(
                'wiki_key' => $wikikey
            ));
            if($jwikitempcount){
                $delres = $jwikitempmodel->delete(array(
                    'wiki_key' => $wikikey
                ));
                if(empty($delres)){
                    $flag = false;
                }
            }
            if(!$flag){
                $this->echojson(array(
                    'rs' => '0',
                    'msg' => '清除wiki临时数据失败',
                ));
            }
            $jwikicattemp = new WikiCategoryTempModel();
            $jwikicattempcount = $jwikicattemp->count(array(
                'wiki_key' => $wikikey
            ));
            if($jwikicattempcount){
                $jwikicattemp->delete(array(
                    'wiki_key' => $wikikey
                ));
                if(empty($delres)){
                    $flag = false;
                }
            }
            if($flag){
                $this->echojson(array(
                    'rs' => '1',
                    'msg' => 'success',
                ));
            }else{
                $this->echojson(array(
                    'rs' => '0',
                    'msg' => '清除wiki分类临时数据失败',
                ));
            }
        }else{
            $this->echojson(array(
                'rs' => '0',
                'msg' => '参数不正确',
            ));
        }
    }


    //修改分类临时表app名称
    public function editappcattempname()
    {
        $id = Request::post('id');
        $acat_name = Request::post('acat_name');
        if (!empty($id) && is_numeric($id)) {
            $jwikicattemp = new WikiCategoryTempModel();
            $ret = $jwikicattemp->update(array(
                'acat_name' => $acat_name
            ), array(
                'id' => $id
            ));
            if ($ret) {
                $this->echojson(array(
                    'rs' => '1',
                    'msg' => 'success',
                ));
            } else {
                $this->echojson(array(
                    'rs' => '0',
                    'msg' => 'failur',
                ));
            }
        } else {
            $this->echojson(array(
                'rs' => '0',
                'msg' => '参数不正确',
            ));
        }
    }

    //修改分类临时表分类icon
    public function editcattempicon()
    {
        $id = Request::post('id');
        $cat_icon = Request::post('caticon');
        if (!empty($id) && is_numeric($id)) {
            $jwikicattemp = new WikiCategoryTempModel();
            $ret = $jwikicattemp->update(array(
                'cat_icon' => $cat_icon
            ), array(
                'id' => $id
            ));
            if ($ret) {
                $this->echojson(array(
                    'rs' => '1',
                    'msg' => 'success',
                ));
            } else {
                $this->echojson(array(
                    'rs' => '0',
                    'msg' => 'failur',
                ));
            }
        } else {
            $this->echojson(array(
                'rs' => '0',
                'msg' => '参数不正确',
            ));
        }
    }

    //检查wiki分类临时表是否有数据
    public function checkaddcattemp()
    {
        $wikikey = Request::getParam('wikikey');
        if ($wikikey) {
            $jwikicattemp = new WikiCategoryTempModel();
            $count = $jwikicattemp->count(array(
                'wiki_key' => $wikikey
            ));
            if ($count) {
                $this->echojson(array(
                    'rs' => '1',
                    'msg' => 'success',
                ));
            } else {
                $this->echojson(array(
                    'rs' => '0',
                    'msg' => 'success',
                ));
            }
        } else {
            $this->echojson(array(
                'rs' => '0',
                'msg' => '参数不正确',
            ));
        }
    }

    ###############################编辑wiki#################################


    //修改分类app名称
    public function editappcatname()
    {
        $id = Request::getParam('id');
        $acat_name = Request::getParam('acat_name');
        if (!empty($id) && is_numeric($id)) {
            $jwikicat = new WikiCategoryModel();
            $ret = $jwikicat->update(array(
                'acat_name' => $acat_name
            ), array(
                'id' => $id
            ));
            if ($ret) {
                $redis = $this->contentRedis();
                $catlist = $jwikicat->selectRow('*', array('id' => $id));
                if ($catlist) {
                    if ($catlist['pname'] == 'root') {
                        $prefix = "jwikiadmin|" . $catlist['wiki_key'] . "|category|root";
                        $valfix = "jwikiadmin|" . $catlist['wiki_key'] . "|category|root|" . $catlist['wcat_name'];
                    } else {
                        $prefix = "jwikiadmin|" . $catlist['wiki_key'] . "|category|" . $catlist['pname'];
                        $valfix = "jwikiadmin|" . $catlist['wiki_key'] . "|category|" . $catlist['pname'] . "|" . $catlist['wcat_name'];
                    }
                    $redis->zRem($prefix, $valfix);
                    $redis->delete($valfix);
                    $redis->zAdd($prefix, $catlist['sort'], $valfix);
                    $redis->set($valfix, json_encode($catlist));
                }
                $this->echojson(array(
                    'rs' => '1',
                    'msg' => 'success',
                ));
            } else {
                $this->echojson(array(
                    'rs' => '0',
                    'msg' => 'failur',
                ));
            }
        } else {
            $this->echojson(array(
                'rs' => '0',
                'msg' => '参数不正确',
            ));
        }
    }

    //修改分类临时表分类icon
    public function editcaticon()
    {
        $id = Request::post('id');
        $cat_icon = Request::post('caticon');
        if (!empty($id) && is_numeric($id)) {
            $jwikicat = new WikiCategoryModel();
            $ret = $jwikicat->update(array(
                'cat_icon' => $cat_icon
            ), array(
                'id' => $id
            ));
            if ($ret) {
                $redis = $this->contentRedis();
                $catlist = $jwikicat->selectRow('*', array('id' => $id));
                if ($catlist) {
                    if ($catlist['pname'] == 'root') {
                        $prefix = "jwikiadmin|" . $catlist['wiki_key'] . "|category|root";
                        $valfix = "jwikiadmin|" . $catlist['wiki_key'] . "|category|root|" . $catlist['wcat_name'];
                    } else {
                        $prefix = "jwikiadmin|" . $catlist['wiki_key'] . "|category|" . $catlist['pname'];
                        $valfix = "jwikiadmin|" . $catlist['wiki_key'] . "|category|" . $catlist['pname'] . "|" . $catlist['wcat_name'];
                    }
                    $redis->zRem($prefix, $valfix);
                    $redis->delete($valfix);
                    $redis->zAdd($prefix, $catlist['sort'], $valfix);
                    $redis->set($valfix, json_encode($catlist));
                }
                $this->echojson(array(
                    'rs' => '1',
                    'msg' => 'success',
                ));
            } else {
                $this->echojson(array(
                    'rs' => '0',
                    'msg' => 'failur',
                ));
            }
        } else {
            $this->echojson(array(
                'rs' => '0',
                'msg' => '参数不正确',
            ));
        }
    }



    ########################################################################
    ###############################后台ajax接口结束##########################
    ########################################################################


    ########################################################################
    ###############################app端接口开始#############################
    ########################################################################


    public function aclist()
    {
        global $GLOBALS;
        $wikikey = Request::getParam('wikikey');
        $queryflag = Request::getParam('queryflag');
        if (!empty($wikikey)) {
            $joymewikismodel = new JoymeWikisModel();
            $joymewikislist = $joymewikismodel->selectRow('wiki_name,game_id', array(
                'wiki_key' => $wikikey
            ));
            if ($joymewikislist) {
                $redis = $this->contentRedis();
                $category = Request::getParam('category');
                $size = 20;
                if ($queryflag) {
                    if ($queryflag == '-1') {
                        $start = 0;
                    } else {
                        $start = $queryflag;
                    }
                } else {
                    $start = 0;
                }
                $end = ($start + $size) - 1;
                $range = array();
                $scoreflag = $min = $start + $size;
                $max = $end + $size;
                $range['max'] = $max;
                $range['min'] = $min;
                $range['scoreflag'] = $scoreflag;
                $range['size'] = $size;

                if (!empty($category)) {
                    $prefix = "jwikiadmin|" . $wikikey . "|category|" . $category;
                    $title = $category;
                } else {
                    $prefix = "jwikiadmin|" . $wikikey . "|category|root";
                    $gamename = $this->querygame($joymewikislist['game_id']);
                    if ($gamename) {
                        $title = $gamename[$joymewikislist['game_id']];
                    } else {
                        $title = "";
                    }
                }
                $catlists = $redis->zRange($prefix, $start, $end);
                if ($catlists) {
                    $result = array();
                    foreach ($catlists as $k => $v) {
                        $list = $redis->get($v);
                        if ($list) {
                            $list = json_decode($list, true);
                            if (empty($list['cat_icon'])) {
                                $result[$k]['picurl'] = "http://static.joyme.com/mobile/wikiapp/cat_icon.png";
                            } else {
                                $result[$k]['picurl'] = $list['cat_icon'];
                            }

                            if (empty($list['acat_name'])) {
                                $result[$k]['gamename'] = "";
                            } else {
                                $result[$k]['gamename'] = $list['acat_name'];
                            }
                            $result[$k]['focusnum'] = "";
                            $result[$k]['time'] = "";
                            $url = "http://hezuo.joyme." . $GLOBALS['domain'] . "/wiki/index.php?c=wiki&a=aclist&wikikey=" . $wikikey . "&category=" . $list['wcat_name'];
                            $result[$k]['ji'] = urlencode($url);
                            $result[$k]['jt'] = "11";
                        }
                    }
                    $nextlists = $redis->zRange($prefix, $min, $max);
                    if ($nextlists) {
                        $range['hasnext'] = true;
                    } else {
                        $range['hasnext'] = false;
                    }
                    $this->echojson(array( 
                        'rs' => '1',
                        'msg' => 'success',
                        'result' => array(
                            'title' => $title,
                            'rows' => $result,
                            'range' => $range
                        )
                    ));
                } else {
                    if (!empty($category)) {
                        $wikicategorymodel = new WikiCategoryModel();
                        $wikicat = $wikicategorymodel->selectRow('acat_name,wcat_name,cat_icon', array(
                            'wcat_name' => $category,
                            'wiki_key' => $wikikey
                        ));
                        if ($wikicat) {
                            $jwikimodel = new JwikiModel($wikikey);
                            $alists = $jwikimodel->getArticleLists($wikicat['wcat_name'], $wikicat['cat_icon'], $start, $size);
                            if ($alists) {
                                $nextdata = $jwikimodel->getArticleNextExsit($wikicat['wcat_name'], $scoreflag, $size);
                                if ($nextdata) {
                                    $range['hasnext'] = true;
                                } else {
                                    $range['hasnext'] = false;
                                }
                                $this->echojson(array(
                                    'rs' => '1',
                                    'msg' => 'success',
                                    'result' => array(
                                        'title' => $wikicat['acat_name'],
                                        'rows' => $alists,
                                        'range' => $range
                                    )
                                ));
                            } else {
                                $this->echojson(array(
                                    'rs' => '1',
                                    'msg' => 'success',
                                    'result' => array(
                                        'title' => $wikicat['acat_name'],
                                        'rows' => array(),
                                    )
                                ));
                            }
                        } else {
                            $this->echojson(array(
                                'rs' => '1',
                                'msg' => 'success',
                                'result' => array(
                                    'title' => $category,
                                    'rows' => array(),
                                )
                            ));
                        }
                    } else {
                        $this->echojson(array(
                            'rs' => '1',
                            'msg' => 'success',
                            'result' => array(
                                'title' => $joymewikislist['wiki_name'],
                                'rows' => array(),
                            )
                        ));
                    }
                }
            }
        } else {
            $this->echojson(array(
                'rs' => '0',
                'msg' => '参数不正确',
            ));
        }
    }

    #####################################################################
    ############################app端接口结束#############################
    #####################################################################


    #####################################################################
    ############################php-java接口开始##########################
    #####################################################################

    //根据wikikey判断wiki是否存在，存在的话，返回wiki的icon
    public function getwikibykey()
    {
        $wikikey = Request::getParam('wikikey');
        if ($wikikey) {
            $joymewikismodel = new JoymeWikisModel();
            $joymewikislist = $joymewikismodel->selectRow("wiki_icon,status", array(
                'wiki_key' => $wikikey
            ));
            if ($joymewikislist) {
                if ($joymewikislist['status'] == 1) {
                    $this->echojson(array(
                        'rs' => '1',
                        'msg' => 'success',
                        'result' => array(
                            'wiki_icon' => $joymewikislist['wiki_icon']
                        )
                    ));
                } else {
                    $this->echojson(array(
                        'rs' => '-1',
                        'msg' => 'result empty',
                        'result' => ''
                    ));
                }
            } else {
                $this->echojson(array(
                    'rs' => '-1',
                    'msg' => 'result empty',
                    'result' => ''
                ));
            }
        } else {
            $this->echojson(array(
                'rs' => '0',
                'msg' => '参数不正确',
                'result' => ''
            ));
        }
    }

    //获取游戏名称
    public function getgamenamebykey()
    {
        $wikikey = Request::getParam('wikikey');
        if ($wikikey) {
            $joymewikismodel = new JoymeWikisModel();
            $joymewikislist = $joymewikismodel->selectRow("game_id", array(
                'wiki_key' => $wikikey
            ));
            if ($joymewikislist) {
                $gamename = $this->querygame($joymewikislist['game_id']);
                if ($gamename) {
                    $this->echojson(array(
                        'rs' => '1',
                        'msg' => 'success',
                        'result' => $gamename[$joymewikislist['game_id']]
                    ));
                } else {
                    $this->echojson(array(
                        'rs' => '0',
                        'msg' => 'result empty',
                        'result' => ''
                    ));
                }
            } else {
                $this->echojson(array(
                    'rs' => '0',
                    'msg' => 'result empty',
                    'result' => ''
                ));
            }
        } else {
            $this->echojson(array(
                'rs' => '0',
                'msg' => '参数不正确',
                'result' => ''
            ));
        }
    }

    //修改wiki的状态0禁用1启用
    public function ewstatusbygid()
    {
        $gameid = Request::getParam('gameid');
        $status = Request::getParam('status');
        if ($gameid && is_numeric($status)) {
            $joymewikimodel = new JoymeWikisModel();
            $list = $joymewikimodel->selectRow('wiki_key', array(
                'game_id' => $gameid
            ));
            if ($list) {
                $res = $joymewikimodel->update(array(
                    'status' => (int)$status
                ), array(
                    'game_id' => $gameid
                ));
                if ($res) {
                    $this->echojson(array(
                        'rs' => '1',
                        'msg' => 'success',
                    ));
                } else {
                    $this->echojson(array(
                        'rs' => '0',
                        'msg' => 'failed',
                    ));
                }
            } else {
                $this->echojson(array(
                    'rs' => '-2',
                    'msg' => 'wiki not exist'
                ));
            }
        } else {
            $this->echojson(array(
                'rs' => '-1',
                'msg' => 'param empty'
            ));
        }
    }

    //修改java游戏关联wiki头图
    public function updateheadicon($wikikey, $gameid, $pic)
    {
        global $GLOBALS;
        if (empty($wikikey)) {
            Log::error(__FILE__, __METHOD__, 'wikikey不能为空');
            return false;
        }
        if (empty($gameid)) {
            Log::error(__FILE__, __METHOD__, 'gameid不能为空');
            return false;
        }
        if (empty($pic)) {
            Log::error(__FILE__, __METHOD__, 'pic不能为空');
            return false;
        }
        $url = 'http://wikiservice.joyme.' . $GLOBALS['domain'] . '/api/wiki/game/updateheadicon';
        $curl = new Curl();
        $res = $curl->Get($url, array(
            'wikikey' => $wikikey,
            'gameid' => $gameid,
            'pic' => $pic,
        ));
        $res = json_decode($res, true);
        if ($res['rs'] == 1) {
            return true;
        } else {
            Log::error(__FILE__, __METHOD__, $res, '修改wiki头图');
            return false;
        }
    }

    //修改java游戏库游戏状态
    public function updategamestatus($wikikey, $gameid, $status)
    {
        global $GLOBALS;
        if (empty($wikikey)) {
            Log::error(__FILE__, __METHOD__, 'wikikey不能为空');
            return false;
        }
        if (empty($gameid)) {
            Log::error(__FILE__, __METHOD__, 'gameid不能为空');
            return false;
        }
        $url = 'http://wikiservice.joyme.' . $GLOBALS['domain'] . '/api/wiki/game/updatestatus';
        $curl = new Curl();
        $res = $curl->Get($url, array(
            'wikikey' => $wikikey,
            'gameid' => $gameid,
            'status' => $status,
        ));
        $res = json_decode($res, true);
        if ($res['rs'] == 1) {
            return true;
        } else {
            Log::error(__FILE__, __METHOD__, $res, '修改java游戏库游戏状态');
            return false;
        }
    }

    //查询游戏名称
    public function querygame($gameids)
    {
        global $GLOBALS;
        if ($gameids) {
            if (is_array($gameids)) {
                $gameids = implode(',', $gameids);
            }
            $gameidsurl = 'http://wikiservice.joyme.' . $GLOBALS['domain'] . '/api/wiki/game/querygame';
            $curl = new Curl();
            $res = $curl->Get($gameidsurl, array(
                'gameids' => $gameids
            ));
            $res = json_decode($res, true);
            if ($res['rs'] == 1) {
                return $res['result'];
            } else {
                Log::error(__FILE__, __METHOD__, $res, '查询游戏名称');
                return false;
            }
        } else {
            return false;
        }
    }

    #####################################################################
    ############################php-java接口结束##########################
    #####################################################################

    //删除redis数据
    public function delredisdata($wikikey)
    {
        if ($wikikey) {
            $redis = $this->contentRedis();
            //wiki分类表
            $wikicatmodel = new WikiCategoryModel();
            $catlists = $wikicatmodel->select('wcat_name,pname', array('wiki_key' => $wikikey), 'sort ASC', '');
            if ($catlists) {
                foreach ($catlists as $k => $catlist) {
                    if ($catlist['pname'] == 'root') {
                        $prefix = "jwikiadmin|" . $wikikey . "|category|root";
                        $valfix = "jwikiadmin|" . $wikikey . "|category|root|" . $catlist['wcat_name'];
                    } else {
                        $prefix = "jwikiadmin|" . $wikikey . "|category|" . $catlist['pname'];
                        $valfix = "jwikiadmin|" . $wikikey . "|category|" . $catlist['pname'] . "|" . $catlist['wcat_name'];
                    }
                    $redis->zRem($prefix, $valfix);
                    $redis->delete($valfix);
                }
            }
        }
    }

    //更新redis数据
    public function updateredisdata()
    {
        $wikikey = Request::getParam('wikikey');
        if ($wikikey) {
            $redis = $this->contentRedis();
            //wiki分类表
            $wikicatmodel = new WikiCategoryModel();
            $catlists = $wikicatmodel->select('*', array('wiki_key' => $wikikey), 'sort ASC', '');
            if ($catlists) {
                foreach ($catlists as $k => $catlist) {
                    if ($catlist['pname'] == 'root') {
                        $prefix = "jwikiadmin|" . $wikikey . "|category|root";
                        $valfix = "jwikiadmin|" . $wikikey . "|category|root|" . $catlist['wcat_name'];
                    } else {
                        $prefix = "jwikiadmin|" . $wikikey . "|category|" . $catlist['pname'];
                        $valfix = "jwikiadmin|" . $wikikey . "|category|" . $catlist['pname'] . "|" . $catlist['wcat_name'];
                    }
                    $redis->zRem($prefix, $valfix);
                    $redis->delete($valfix);
                    $redis->zAdd($prefix, $catlist['sort'], $valfix);
                    $redis->set($valfix, json_encode($catlist));
                }
                echo '操作成功';
            }
        }
    }

    //更新redis数据
    public function upallredisdata()
    {
        $redis = $this->contentRedis();
        //wiki分类表
        $wikicatmodel = new WikiCategoryModel();
        $catlists = $wikicatmodel->select('*', array(), 'sort ASC', '');
        if ($catlists) {
            foreach ($catlists as $k => $catlist) {
                if ($catlist['pname'] == 'root') {
                    $prefix = "jwikiadmin|" . $catlist['wiki_key'] . "|category|root";
                    $valfix = "jwikiadmin|" . $catlist['wiki_key'] . "|category|root|" . $catlist['wcat_name'];
                } else {
                    $prefix = "jwikiadmin|" . $catlist['wiki_key'] . "|category|" . $catlist['pname'];
                    $valfix = "jwikiadmin|" . $catlist['wiki_key'] . "|category|" . $catlist['pname'] . "|" . $catlist['wcat_name'];
                }
                $redis->zRem($prefix, $valfix);
                $redis->delete($valfix);
                $redis->zAdd($prefix, $catlist['sort'], $valfix);
                $redis->set($valfix, json_encode($catlist));
                echo $k . ": " . $catlist['wcat_name'] . "执行完成<br/>";
            }
        } else {
            echo "no data";
        }
    }

    //连接redis
    protected function contentRedis()
    {
        $redis = new Redis();
        $redis->connect($GLOBALS['redis_host'], $GLOBALS['redis_port']);
        if($GLOBALS['redis_password']){
            $redis->auth($GLOBALS['redis_password']);
        }
        return $redis;
    }

    private function getUptoken()
    {
        $bucket = $GLOBALS['config']['qiniu']['bucket'];
        return Qiniu_Utils::Qiniu_UploadToken($bucket);
    }


    protected function echojson($data)
    {
        if (!empty($data)) {
            echo json_encode($data);
            exit();
        }
    }

}
