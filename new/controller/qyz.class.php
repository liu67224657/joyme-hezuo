<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2016/11/16
 * Time: 10:24
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'app.class.php');
use Joyme\core\Request;
use Joyme\page\Page;

class qyz extends app{

    //每页显示条数
    public $pb_show_num = 8;

    //每日限制次数
    public $votes_umber = 5;

    //密钥
    public $sign = '~(@jm@qyz!';

    //活动结束时间
    public $end_time = '2016-12-01';

    //静态文件地址
    public $static_path = '';

    //服务器列表数据获取接口
    public $getServerListUrl = '';

    //检测角色ID和服务器接口
    public $getCheckUserUrl = '';
    //分享JS路径
    public $shareJsPath = '';
    //***微信认证
    //AppID
    public $AppID = '';
    //AppSecret
    public $AppSecret = '';
    //access_token
    public $access_token = '';
    //ticket
    public $ticket = '';
    //openid
    public $openid = '';
    //是否来自分享
    public $isFromShare = false;


    function __construct()
    {
        global $GLOBALS;

        if($GLOBALS['domain'] == 'beta'){
            $this->AppID = 'wx6adc249f511f2c93';
            $this->AppSecret = 'fc467e22b19d21a5e045f598744e5841';
            $this->shareJsPath = 'http://hezuo.joyme.beta/new/static/script/jweixin-1.1.0.js';
            $this->getServerListUrl = 'http://124.193.167.190:9003/hr/getServeridlist';
            $this->getCheckUserUrl = 'http://124.193.167.190:9003/hr/requestRoleinfo';
            $this->static_path = 'http://static.joyme.com/mobile/cms/qyzmrt/';
        }elseif($GLOBALS['domain'] == 'com'){
            $this->AppID = 'wx529ba0bd21499c1a';
            $this->AppSecret = 'c54c523397a9a2330a8d6856dceadf2c';
            $this->shareJsPath = 'http://hezuo.joyme.com/new/static/script/jweixin-1.1.0.js';
            $this->getServerListUrl = 'http://120.92.19.28:10005/hr/getServeridlist';
            $this->getCheckUserUrl = 'http://120.92.19.28:10005/hr/requestRoleinfo';
            $this->static_path = 'http://static.joyme.com/mobile/cms/qyzmrt/';
        }else{
            $this->AppID = 'wxa0a9c845857936c4';
            $this->AppSecret = 'cca809bd9c5c4901bf879f13d863a23a';
            $this->shareJsPath = 'http://hezuo2.tunnel.qydev.com/new/static/script/jweixin-1.1.0.js';
            $this->getServerListUrl = 'http://124.193.167.190:9003/hr/getServeridlist';
            $this->getCheckUserUrl = 'http://124.193.167.190:9003/hr/requestRoleinfo';
            $this->static_path = 'http://static.joyme.com/mobile/cms/qyzmrt/';
        }

        $this->isFromShare = Request::getParam('is_share');
        parent::__construct();
    }


    //首页展示
    function index(){

        $data['is_share'] = $this->isFromShare;
        if(!$data['is_share']){
            $openid = Request::getParam('openid');
            if($openid) {
                $data['openid'] = $openid;
                setcookie('qyz_openid',$openid,time()+3600*24);
            }
        }
        $data['shareJsPath'] = $this->shareJsPath;
        $data['conf'] = $this->generate();
        $data['static_path'] = $this->static_path;
        render($data,'web','qyz/index');
    }


    //参赛必看
    function mustSee(){

        $data['is_share'] = $this->isFromShare;
        $data['type'] = Request::getParam('type',0);
        $data['static_path'] = $this->static_path;
        $data['shareJsPath'] = $this->shareJsPath;
        $data['conf'] = $this->generate();
        render($data,'web','qyz/mustsee');
    }

    //丰厚奖励
    function reward(){

        $data['is_share'] = $this->isFromShare;
        $data['static_path'] = $this->static_path;
        $data['shareJsPath'] = $this->shareJsPath;
        $data['conf'] = $this->generate();
        render($data,'web','qyz/reward');
    }


    //报名页面
    function signUp(){

        if($_POST){
            $data['user_id'] = intval(Request::getParam('user_id'));
            $data['user_name'] = Request::getParam('user_name');
            $area_code = Request::getParam('area_code');
            $area_codes = explode('|',$area_code);
            $data['area_code'] = $area_codes[0];
            $data['server_id'] = $area_codes[1];
            $data['occup'] = Request::getParam('occup');
            $data['family'] = Request::getParam('family');
            $data['decla'] = Request::getParam('decla');
            $data['head_portr'] = Request::getParam('head_portr');
            $data['create_time'] = time();
            $model = M('ActiveQyzUsers');
            $result = $model->addUserInfo($data);
            self::outputResult($result);
        }else{
            $data['is_share'] = $this->isFromShare;
            $data['conf'] = $this->generate();
            $redis = $this->contentRedis();
            $res = json_decode($this->getServerInfo($redis, 'serverList',2,$this->getServerListUrl ),true);
            if(isset($res['serveridlist'])){
                $data['serveridlist'] = $res['serveridlist'];
            }
            $data['endtime'] = strtotime($this->end_time);
            $data['static_path'] = $this->static_path;
            $data['shareJsPath'] = $this->shareJsPath;
            $data['time'] = time();
            render($data,'web','qyz/signup');
        }
    }


    //选手风采
    function layerList(){

        $data['is_share'] = $this->isFromShare;
        $model = M('ActiveQyzUsers');
        $pb_page = Request::get('pb_page',1); //获取当前页码
        $conditions['sort'] = intval(Request::getParam('sort',1));  //排序 1最新 2最热
        $conditions['user_id'] = Request::getParam('userid');  //角色ID搜索
        $total = $model->allUserList($conditions,true);
        $data['item'] = $model->allUserList($conditions,false,$pb_page,$this->pb_show_num);
        $data['page']['max_page'] = ceil($total / $this->pb_show_num);
        $data['param'] = $conditions;
        $data['endtime'] = strtotime($this->end_time);
        $data['time'] = time();
        $data['static_path'] = $this->static_path;
        $data['conf'] = $this->generate();
        $data['shareJsPath'] = $this->shareJsPath;

        $redis = $this->contentRedis();
        //获取鲜花数
        foreach($data['item'] as $k=>&$v){
            if($v['server_id']){
                $url = $this->getCheckUserUrl.'?ServerId='.$v['server_id'].'&RoleId='.$v['user_id'];
                $res = json_decode($this->getServerInfo($redis, $v['user_id'],1,$url ),true);
                if(isset($res['code']) && $res['code'] == 0){
                    $v['flower'] = $res['data']['flower'];
                }
            }else{
                $v['flower'] = 0;
            }
        }
        if(!Request::get('ajax')){
            render($data,'web','qyz/layerlist');
        }else{
            echo json_encode($data);
            exit;
        }
    }


    //投票
    function  active_vote(){

        $votemodel = M('ActiveQyzVote');
        $user_id = intval(Request::getParam('user_id'));
        //记录数据
        $arr = array();
        if($_COOKIE['qyz_openid']){
            $arr = explode('?',$_COOKIE['qyz_openid']);
        }
        if(count($arr)>=2){
            $openid = $arr[0];
        }else{
            $openid = $_COOKIE['qyz_openid'];
        }
        $data['openid'] = $openid;
        $data['time'] = date('Y-m-d');
        //查看是否可以投票
        if($votemodel->getCountNumber($data) < $this->votes_umber){
            $usermodel = M('ActiveQyzUsers');
            $array['user_id'] = $user_id;
            $array['openid'] = $openid;
            $array['time'] = date('Y-m-d');
            //增加投票记录
            $result = $votemodel->addVoteInfo($array);
            if($result){
                //更新投票数
                if($usermodel->updateUserVoteNum( $user_id )){
                    self::outputResult($result,'',1);
                }else{
                    self::outputResult(true,'',2);
                }
            }else{
                self::outputResult(true,'',2);
            }
        }else{
            self::outputResult(true,'',3);
        }
    }

    //判断用户存不存在
    function userExists(){

        $usermodel = M('ActiveQyzUsers');
        $user_id = intval(Request::getParam('user_id'));
        $area_code = Request::getParam('area_code');
        $area_codes = explode('|',$area_code);
        $where['user_id'] = $user_id;
        $result = $usermodel->getUserById($where);
        if($result){
            self::outputResult(true,'',0);
        }else{
            $url = $this->getCheckUserUrl.'?ServerId='.$area_codes[1].'&RoleId='.$user_id;
            $res = json_decode($this->http_get($url),true);
            //用户不存在
            if(isset($res['code']) && $res['code']== 1){
                self::outputResult(true,'',1);
            }elseif(isset($res['code']) && $res['code']== 2){
                //服务器不存在
                self::outputResult(true,'',2);
            }elseif(isset($res['code']) && $res['code']== 0){
                //存在
                self::outputResult(true,'',3);
            }
        }
    }


    //审核列表
    public function adminCheckList(){

        $model = M('ActiveQyzUsers');
        if($_POST){
            $id = Request::getParam('id');
            $where['id'] = $id;
            $result = $model->offUser($where);
            self::outputResult($result);
        }else{
            $pb_page = Request::get('pb_page',1); //获取当前页码
            $conditions['status'] = 1;  //角色ID搜索
            $total = $model->allUserList($conditions,true);
            $data['item'] = $model->allUserList($conditions,false,$pb_page,$this->pb_show_num);
            $_page = new Page(array('total' => $total,'perpage'=>$this->pb_show_num,'nowindex'=>$pb_page,'pagebarnum'=>8));
            $data['page'] = $_page->show(2);
            render($data,'web','qyz/adminlist');
        }
    }


    //连接redis
    function contentRedis(){

        $redis = new Redis();
        $redis->connect($GLOBALS['redis_host'], $GLOBALS['redis_port']);
        return $redis;
    }

    //更新服务器列表数据/用户鲜花数
    function getServerInfo($redis = false, $key , $time,$url){

        if($redis){
            $Prefix = "getServerInfo|".$GLOBALS['domain']."|". __CLASS__.'|'.$key;
            if(!$redis->exists($Prefix) || ($redis->exists($Prefix) && $redis->get($Prefix)=='')){
                //获取服务器数据
                $data = $this->http_get($url);
                $redis->set($Prefix,$data);
                $redis->expire($Prefix,$this->expiresTestingTime( $time ));
            }else{
                $data = $redis->get($Prefix);
            }
        }else{
            $data = $this->http_get($url);
        }
        return $data;
    }


    //计算有效期
    function expiresTestingTime( $type = 1 ){

        if($type == 1){
            $time = mktime(00,00,00,date("m"),date("d")+1,date("Y"))-time();
        }else{
            $time = 60*5;
        }
        return $time;
    }

    //redis缓存token，jsapi_tiket等
    public function generate(){

        $redis = $this->contentRedis();
        if($redis){
            $Prefix = "getToken|".$GLOBALS['domain']."|".$_SERVER['REQUEST_URI'];
            if(!$redis->exists($Prefix) || ($redis->exists($Prefix) && $redis->get($Prefix)=='')){
                //获取服务器数据
                $data = $this->getToken();
                $redis->set($Prefix,json_encode($data));
                $redis->expire($Prefix,7200);
            }else{
                $data = json_decode($redis->get($Prefix),true);
            }
        }else{
            $data = $this->$this->generate();
        }
        return $data;
    }


    public function getToken(){

        //获取token
        $result = $this->http_get('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->AppID.'&secret='.$this->AppSecret);
        $json = json_decode($result,true);
        if(isset($json['access_token'])){
            $this->access_token = $json['access_token'];
            //获取ticket
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$json['access_token'];
            $res = json_decode ( $this->http_get ( $url ) );
            $this->ticket = $res->ticket;
        }
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $signature = $this->getRandChar(16);
        return  $this->getSignPackage($this->ticket,$url,time(),$signature);
    }


    //返回配置串
    private function getSignPackage($jsapiTicket,$url,$timestamp,$nonceStr) {

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1 ( $string );

        $signPackage["appId"] = $this->AppID;
        $signPackage["nonceStr"] = $nonceStr;
        $signPackage["timestamp"] = $timestamp;
        $signPackage["signature"] = $signature;
        $signPackage["rawString"] = $string;
        $signPackage["url"] = $url.'&is_share=true';

        return $signPackage;
    }

    //随机字符串
    function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }

    //刷赞
    function updateUote(){

        $userid = Request::getParam('userid');
        $num = Request::getParam('num');
        if($userid && $num){
            $model = M('ActiveQyzUsers');
            $result = $model->updateUserVoteByAdmin( $userid,$num );
            if($result){
                echo 'ok';
            }else{
                echo 'error';
            }
        }else{
            echo 'null param!';
        }
    }


    private function http_get($url){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }
}