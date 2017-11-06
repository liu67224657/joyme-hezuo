<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/9/6
 * Time: 18:03
 */
if (!defined('IN')) die('bad request');

include_once( AROOT . 'controller' . DS . 'free.class.php' );

use Joyme\core\Request;
use Joyme\page\Page;

class friendstool extends free{

    public $perpage= 50;

    function friendsList(){

        global $GLOBALS;
        $pb_page = Request::getParam('pb_page',1);
        $model = M('FrendsTool');
        $conditions['wiki_key'] = Request::getParam('wiki_key');
        if(!$conditions['wiki_key']){
            echo 'wiki_key不能为空!';
            exit;
        }
        $conditions['keywords'] = addslashes(Request::getParam('keywords'));
        $conditions['platform'] = Request::getParam('platform');
        $conditions['system'] = Request::getParam('system');
        $conditions['sort'] = Request::getParam('sort');
        $total = $model->friendsList($conditions,true);
        $data['is_new'] = Request::getParam('type',false);
        $data['item'] = $model->friendsList($conditions,false,$pb_page,$this->perpage);
        $data['info'] = $model->getSiteNotice($conditions['wiki_key']);
        $_page = new Page(array('total' => $total,'perpage'=>$this->perpage,'nowindex'=>$pb_page,'pagebarnum'=>10));
        $data['page_str'] = $page_str = $_page->show(2);
        $data['static_css_path'] = $GLOBALS['static_url'];
        $data['static_js_path'] = "http://".$_SERVER['HTTP_HOST'];
        $data['url'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
        $data['conditions'] = $conditions;
        $data['ip'] = $_SERVER["REMOTE_ADDR"];
        $data['is_onclick'] = true;
        if($_COOKIE['JoymeAddfriends'] == $data['ip']){
            $data['is_onclick'] = false;
        }
        $forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);
        if(!preg_match("/mobile/",$forasp)){
            render($data,'web','tools/addfriends');
        }else{
            render($data,'web','tools/wapaddfriends');
        }
    }

    function addFriends(){

        $platform_type = intval(Request::getParam('platform'))==1?1:2;
        $system_type = intval(Request::getParam('system'))==1?1:3;
        $recruit_info = addslashes(Request::getParam('recruit_info'));
        $number = Request::getParam('number');
        $wiki_key = Request::getParam('wiki_key');
        if(!empty($platform_type) && !empty($number) && !empty($wiki_key)){
            $model = M('FrendsTool');
            $result = $model->addFrendsInfo($platform_type,$system_type,$number,$wiki_key,$recruit_info);
            self::outputResult($result);
        }else{
            self::optPutEmptyParam();
        }
    }

    function setTop(){

        $id = intval(Request::getParam('id'));
        $wiki_key = Request::getParam('wiki_key');
        if(empty($id) || empty($wiki_key)){
            self::optPutEmptyParam();
        }
        $model = M('FrendsTool');
        $result = $model->updateTime($id,$wiki_key);
        self::outputResult($result);
    }


    function wapListData(){

        $model = M('FrendsTool');
        $pb_page = intval(Request::getParam('pb_page',2));
        $conditions['wiki_key'] = Request::getParam('wiki_key','gundam');
        $total = $model->friendsList($conditions,true);
        $data['item'] = $model->friendsList($conditions,false,$pb_page,$this->perpage);
        $data['page']['maxpage'] = ceil($total/$this->perpage);
        foreach($data['item'] as $k=>&$v){
            $v['time'] = str_replace('-','/',substr(date("Y-m-d H:i:s",$v['time']),5,11));
        }
        echo json_encode($data);exit;
    }

    //后台管理
    function management(){

        $model = M('FrendsTool');
        $data['item'] = $model->getSiteInfo();
        render($data,'web','tools/infomanagement');
    }

    function editInfo(){

        $model = M('FrendsTool');
        if($_POST){
            $id = Request::getParam('update_id');
            $title = addslashes(Request::getParam('title'));
            $siteId = addslashes(Request::getParam('siteId'));
            $notice = addslashes(Request::getParam('notice'));
            $this->tipInfo($model->editInfoById($id,$title,$siteId,$notice));
        }else{
            $id = Request::getParam('id',1);
            $data['item'] = $model->getInfoById($id);
            render($data,'web','tools/edit_info');
        }
    }

    function showadd(){

        $model = M('FrendsTool');
        if($_POST){
            $wiki_key = addslashes(Request::getParam('wiki_key'));
            $title = addslashes(Request::getParam('title'));
            $siteId = addslashes(Request::getParam('siteId'));
            $notice = addslashes(Request::getParam('notice'));
            $this->tipInfo($model->addSiteInfo($wiki_key,$title,$siteId,$notice));
        }else{
            render('','web','tools/add_info');
        }
    }

    function addInfo(){

        $model = M('FrendsTool');
        $wiki_key = addslashes(Request::getParam('wiki_key'));
        $title = addslashes(Request::getParam('title'));
        $notice = addslashes(Request::getParam('notice'));
        $this->tipInfo($model->addSiteInfo($wiki_key,$title,$notice));
    }

    function delInfo(){

        $id = Request::getParam('id');
        $model = M('FrendsTool');
        $this->tipInfo($model->deleteById($id));
    }


    function checkWord(){

        $wiki_key = addslashes(Request::getParam('recruit_info'));
        $file = file(__DIR__.'/../lib/SensitiveWords/SensitiveWords.txt');
        $return = array(
            'rs'=>0,
            'data'=>''
        );
        if($file){
            foreach($file as $k=>$v){
                 $word[] = explode('|',$v);
            }
            foreach($word as $ks=>$vs){
               foreach($vs as $kss=>$vss){
                   if(!empty($vss)){
                       if(substr_count($wiki_key,$vss)){
                           $pb = '';
                           for($i=0;$i<=mb_strlen($vss,'utf-8');$i++){
                               $pb.= '*';
                           }
                           $result = str_replace($vss,$pb,$wiki_key);
                           $return = array(
                               'rs'=>1,
                               'data'=>$result
                           );
                           break;
                       }
                   }
               }
               if($return['rs']==1){
                   break;
               }
            }
        }
        echo json_encode($return);
        exit;
    }

    function tipInfo($result){

        echo '<meta charset="UTF-8">';
        if($result){
            echo "操作成功!!!";
        }else{
            echo "操作失败!!!";
        }
        echo '<br>';
        echo '<a href="?c=friendstool&a=management">返回列表</a>';
    }
}