<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/9/7
 * Time: 15:54
 */
if (!defined('IN'))
    die('bad request');

use Joyme\db\JoymeModel;

class FrendsToolModel extends JoymeModel{

    public $fields = array(
    );

    public $tableName = 'frendstool';

    public function __construct() {

        $this->db_config = array(
            'hostname' => $GLOBALS['config']['db']['db_host'],
            'username' => $GLOBALS['config']['db']['db_user'],
            'password' => $GLOBALS['config']['db']['db_password'],
            'database' => $GLOBALS['config']['db']['db_name']
        );
        parent::__construct();
    }

    public function addFrendsInfo($platform_type,$system_type,$number,$wiki_key,$recruit_info){

        $data = array(
            'platform_type'=>$platform_type,
            'system_type'=>$system_type,
            'number'=>$number,
            'wiki_key'=>$wiki_key,
            'time'=>time(),
            'flag'=>0,
            'recruit_info'=>$recruit_info
        );
        return $this->insert($data);
    }

    public function friendsList($conditions,$total,$page=1,$pageage=10){

        $skip = intval(($page-1)*$pageage);
        if(!empty($conditions['wiki_key'])){
            $where['wiki_key'] = $conditions['wiki_key'];
        }
        if(!empty($conditions['keywords'])){
            $where['number'] = $conditions['keywords'];
        }
        if(!empty($conditions['platform'])){
            $where['platform_type'] = $conditions['platform'];
        }
        if(!empty($conditions['system'])){
            $where['system_type'] = $conditions['system'];
        }
        if(!empty($conditions['sort'])){
            $order = '';
        }else{
            $order = 'time DESC';
        }
        $where['flag'] = array('eq',0);
        if($total){
            $count = $this->count($where);
        }else{
            $arr = $this->select('*',$where,$order,$pageage,$skip);
        }
        $result = $total?$count:$arr;
        return $result;
    }

    function updateTime($id,$wiki_key){

        $data = array(
            'time'=>time()
        );
        $where = array(
            'id'=>$id,
            'wiki_key'=>$wiki_key
        );
        return $this->update($data, $where);
    }

    function getSiteInfo(){

        $where = array(
            'flag'=>1,
        );
        return $this->select('*',$where);
    }

    function editInfoById($id,$title,$siteId,$notice){

        $data = array(
            'title'=>$title,
            'siteId'=>$siteId,
            'notice'=>$notice
        );
        $where = array(
            'id'=>$id
        );
        return $this->update($data,$where);
    }

    function getInfoById($id){

        $where = array(
            'id'=>$id,
        );
        return $this->selectRow('*',$where);
    }

    function addSiteInfo($wiki_key,$title,$siteId,$notice){

        $data = array(
            'platform_type'=>rand(0,999),
            'system_type'=>rand(0,999),
            'number'=>rand(0,999),
            'title'=>$title,
            'siteId'=>$siteId,
            'notice'=>$notice,
            'wiki_key'=>$wiki_key,
            'time'=>time(),
            'flag'=>1
        );
        return $this->insert($data);
    }

    function deleteById($id){

        $where = array(
            'id'=>$id
        );
        return $this->delete($where);
    }

    function getSiteNotice($wiki_key){

        $where = array(
            'flag'=>1,
            'wiki_key'=>$wiki_key
        );
        return $this->selectRow('*',$where);
    }
}