<?php

/**
 * Description of po
 * 
 * 
 * @author clarkzhao
 * @date 2015-01-29 06:18:49
 * @copyright joyme.com
 */
//
//DEV:db001.dev:3306  user：advertiseser  password：dev
//Prod：db001.prod:3311   user：advertiseser   password：2QWdf#Z9fc0o*$zE
//dev
//$config['host'] = 'db001.dev';
//$config['port'] = 3306;
//$config['username'] = 'advertiseser';
//$config['password'] = 'dev';


//prod
$config['host'] = 'alyweb005.prod';
$config['port'] = 3306;
$config['username'] = 'advertiseser';
$config['password'] = '2QWdf#Z9fc0o*$zE';


$appkey = $_REQUEST['appkey'];


$conn = mysql_connect($config['host'] . ":" . $config['port'], $config['username'], $config['password']);
if (!$conn) {
    die('连接建立错误: ' . mysql_error());
} else {
    //echo "good connect";
}
mysql_select_db('ADVERTISE', $conn);


//$data = array('cid'=>1,'title'=>'这是一条测试广告','img'=>'http://picm.photophoto.cn/014/007/002/0070020208.jpg','url'=>'http://www.qq.com');
//        $fReturn = array('code'=>0, 'msg'=>'开机广告','data'=>$data);
        
$querySQL = "select * from app_publish left join app_advertise on app_publish.advertise_id=app_advertise.advertise_id where publish_type =0 and appkey='".  mysql_real_escape_string($appkey, $conn)."' and app_publish.remove_status = 'n'";
$result = mysql_query($querySQL, $conn);
$curentYear = date('Y', time());
$data = array();
if ($result != false) {//查询成功
    while ($row = mysql_fetch_array($result)) {
        $data = array('cid'=>1,'title'=>'这是广告','img'=>$row['advertise_picurl2'],'url'=>$row['advertise_url']);
        break;
    }
}
mysql_close($conn);
if(count($data)==0){
    $fReturn = array('code'=>1, 'msg'=>'无广告','data'=>array());
}else{
    $fReturn = array('code'=>0, 'msg'=>'开机广告','data'=>$data);
}

echo json_encode($fReturn);

?>