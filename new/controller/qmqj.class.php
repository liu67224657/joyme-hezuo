<?php
/**
 * Created by PhpStorm.
 * 全名奇迹-名人堂
 * User: xinshi
 * Date: 2016/11/16
 * Time: 10:24
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'app.class.php');
use Joyme\core\Request;
use Joyme\page\Page;

class qmqj extends app{

    //每页显示条数
    public $pb_show_num = 8;
    //每日限制次数
    public $votes_umber = 5;
    //活动结束时间
    public $end_time = '2016-12-17';
    //静态文件地址
    public $static_path = '';
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
    //回掉地址
    public $callback = '';
    //token请求次数
    public $Tokenkey = 'QMQM-GET-TOKEN-COUNT';


    function __construct()
    {
        global $GLOBALS;

        if($GLOBALS['domain'] == 'com'){
            //$this->AppID = 'wx529ba0bd21499c1a';
            //$this->AppSecret = 'c54c523397a9a2330a8d6856dceadf2c';
            $this->AppID = 'wxe6eafdebe1a95bd5';
            $this->AppSecret = 'd878b4b82eb36fe487e909cf5886542e';
            $this->shareJsPath = 'http://hezuo.joyme.com/new/static/script/jweixin-1.1.0.js';
            $this->static_path = 'http://static.joyme.com/mobile/cms/qmqjmrt/';
        }elseif($GLOBALS['domain'] == 'beta'){
            $this->AppID = 'wxd515e32d39e2478e';
            $this->AppSecret = '02fb171628008cc4d01fbdf9ec526877';
            $this->shareJsPath = 'http://hezuo.joyme.com/new/static/script/jweixin-1.1.0.js';
            $this->static_path = 'http://static.joyme.beta/mobile/cms/qmqjmrt/';
        }else{
            $this->AppID = 'wxaa8ada5cd2f0e662';
            $this->AppSecret = 'b6c875092003dbb116075f5c6cbb85ca';
            $this->shareJsPath = 'http://hezuo2.tunnel.qydev.com/new/static/script/jweixin-1.1.0.js';
            $this->static_path = 'http://share-ued.enjoyf.com/UED/%E8%96%9B%E7%A7%80%E6%A2%85/%E6%89%8B%E6%9C%BA%E7%AB%AF/%E5%85%A8%E6%B0%91%E5%A5%87%E8%BF%B9MU%E5%90%8D%E4%BA%BA%E5%A0%82/';
        }
        $this->callback = 'http://'.$_SERVER['HTTP_HOST'].'/new/?c=qmqj&a=layerList';
        $this->isFromShare = Request::getParam('is_share');
        parent::__construct();
    }


    //获取code
    public function getUserCode(){

        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->AppID.'&redirect_uri='.urlencode($this->callback).'&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
        header("Location:$url");
    }

    //接收微信请求
    public function weixin( $code ){

        if(!empty($code)){
            $openid = $_COOKIE[$code];
            if($openid){
                return $openid;
            }else{
                $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->AppID.'&secret='.$this->AppSecret.'&code='.$code.'&grant_type=authorization_code';
                $res = json_decode($this->http_get($url),true);
                if(isset($res['openid'])){
                    setcookie($code,$res['openid'],time()+3600);
                    return $res['openid'];
                }
            }
        }
        return false;
    }


    //首页展示
    function index(){

        $data['is_share'] = $this->isFromShare;
        $data['shareJsPath'] = $this->shareJsPath;
        $data['conf'] = $this->generate();
        $data['static_path'] = $this->static_path;
	//var_dump($data);
	//die;
        render($data,'web','qmqj/index');
    }


    //参赛必看
    function mustSee(){

        $data['is_share'] = $this->isFromShare;
        $data['type'] = Request::getParam('type',0);
        $data['static_path'] = $this->static_path;
        $data['shareJsPath'] = $this->shareJsPath;
        $data['conf'] = $this->generate();
        render($data,'web','qmqj/mustsee');
    }


    //丰厚奖励
    function reward(){

        $data['is_share'] = $this->isFromShare;
        $data['static_path'] = $this->static_path;
        $data['shareJsPath'] = $this->shareJsPath;
        $data['conf'] = $this->generate();
        render($data,'web','qmqj/reward');
    }


    //报名页面
    function signUp(){

        if($_POST){
            $data['user_id'] = intval(Request::getParam('user_id'));
            $data['user_name'] = Request::getParam('user_name');
            $data['area_code'] = Request::getParam('area_code');

            $data['occup'] = Request::getParam('occup');
            $data['family'] = Request::getParam('family');
            $data['decla'] = Request::getParam('decla');
            $data['theme'] = Request::getParam('theme');
            $data['head_portr'] = Request::getParam('head_portr');
            $data['create_time'] = time();
            $model = M('ActiveQmqjUsers');
            $result = $model->addUserInfo($data);
            self::outputResult($result);
        }else{
            $data['is_share'] = $this->isFromShare;
            $data['conf'] = $this->generate();
            $data['endtime'] = strtotime($this->end_time);
            $data['static_path'] = $this->static_path;
            $data['shareJsPath'] = $this->shareJsPath;
            $data['time'] = time();
	    //var_dump($data);
	    //die;
            render($data,'web','qmqj/signup');
        }
    }


    //选手风采
    function layerList(){

        $data['code'] = Request::getParam('code');
        $data['is_share'] = $this->isFromShare;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') != false && !$data['code'] && !Request::get('ajax')) {
            $this->getUserCode();
        }
        $model = M('ActiveQmqjUsers');
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
        if(!Request::get('ajax')){
            render($data,'web','qmqj/layerlist');
        }else{
            echo json_encode($data);
            exit;
        }
    }


    //投票
    function  active_vote(){

        $code = Request::getParam('code');
        $user_id = intval(Request::getParam('user_id'));
        if(!empty($code) && !empty($user_id)){
            $openid = $this->weixin($code);
            if(!empty($openid)){
                $votemodel = M('ActiveQmqjVote');
                $data['openid'] = $openid;
                $data['time'] = date('Y-m-d');
                //查看是否可以投票
                if($votemodel->getCountNumber($data) < $this->votes_umber){
                    $usermodel = M('ActiveQmqjUsers');
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
            }else{
                self::outputResult(true,'',2);
            }
        }else{
            self::outputResult(true,'',2);
        }
    }

    //判断用户存不存在
    function userExists(){

        $usermodel = M('ActiveQmqjUsers');
        $user_id = intval(Request::getParam('user_id'));
        $result = false;
        if( !empty($user_id)){
            $where['user_id'] = $user_id;
            $result = $usermodel->getUserById($where);
        }
        self::outputResult($result);
    }


    //审核列表
    public function adminCheckList(){

        $model = M('ActiveQmqjUsers');
        $user_id = intval(Request::getParam('user_id'));
        if($_POST){
            $id = Request::getParam('id');
            $where['id'] = $id;
            $result = $model->offUser($where);
            self::outputResult($result);
        }else{
            if($user_id){
                $conditions['user_id'] = $user_id;
            }
            $pb_page = Request::get('pb_page',1); //获取当前页码
            $conditions['status'] = 1;  //角色ID搜索
            $total = $model->allUserList($conditions,true);
            $data['item'] = $model->allUserList($conditions,false,$pb_page,$this->pb_show_num);
            $_page = new Page(array('total' => $total,'perpage'=>$this->pb_show_num,'nowindex'=>$pb_page,'pagebarnum'=>8));
            $data['page'] = $_page->show(2);
            render($data,'web','qmqj/adminlist');
        }
    }


    //连接redis
    function contentRedis(){

        $redis = new Redis();
        $redis->connect($GLOBALS['redis_host'], $GLOBALS['redis_port']);
        return $redis;
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
            $Prefix = "QMQJ331|getToken|".$GLOBALS['domain']."|".$_SERVER['REQUEST_URI'];
            if(!$redis->exists($Prefix) || ($redis->exists($Prefix) && $redis->get($Prefix)=='')){
                //获取服务器数据
                $data = $this->getToken();
                $redis->set($Prefix,json_encode($data));
                $redis->expire($Prefix,7200);
                //记录token请求次数
                $redis->incrbyfloat($this->Tokenkey,1);
            }else{
                $data = json_decode($redis->get($Prefix),true);
            }
        }else{
            $data = $this->generate();
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
        $signPackage["url"] = 'http://hezuo.joyme.com/new/?c=qmqj&a=layerList&is_share=true';

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

    public function testGetRedis(){

        $redis = $this->contentRedis();
//        $redis->delete($key);
//        $a = $redis->incrbyfloat($this->Tokenkey,1);
        $a = $redis->get($this->Tokenkey);
        var_dump($a);
    }
}
