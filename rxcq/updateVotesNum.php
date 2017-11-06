<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/8/3
 * Time: 10:19
 */
header("Content-type:text/html;charset=utf-8");

$forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);
if(preg_match("/mobile/",$forasp)){
    die("只能通过PC端访问！");
}
include_once("config/config.php");
$memcach = new memcache();
$memcach->connect($config['mem_host'],$config['mem_port']) or die('连接失败');
$flag = !empty($_POST['flag'])?$_POST['flag']:'';

if($_POST && $flag=='joymevote'){
    $id = !empty($_POST['id'])?$_POST['id']:'';
    $num = is_numeric($_POST['num'])?$_POST['num']:'';
    if(empty($id) || $num<0){
        $data = array("rs"=>3,'msg'=>'ParamError');
    }else{
        if($id == 1){
            $key = 'HezuoRxcqKey_1';
        }elseif($id == 2){
            $key = 'HezuoRxcqKey_2';
        }else{
            $key = 'HezuoRxcqKey_3';
        }
        if($memcach->set($key,$num)){
            $result = true;
        }else{
            $result = false;
        }
        if($result){
            $data = array("rs"=>0,'msg'=>'success');
        }else{
            $data = array("rs"=>1,'msg'=>'fail');
        }
    }
    echo json_encode($data);
    $memcach->close();
    exit;
}else{
    $data = $memcach->get(array('HezuoRxcqKey_1','HezuoRxcqKey_2','HezuoRxcqKey_3'));
    $newdata['HezuoRxcqKey_1'] = !empty($data['HezuoRxcqKey_1'])?$data['HezuoRxcqKey_1']:0;
    $newdata['HezuoRxcqKey_2'] = !empty($data['HezuoRxcqKey_2'])?$data['HezuoRxcqKey_2']:0;
    $newdata['HezuoRxcqKey_3'] = !empty($data['HezuoRxcqKey_3'])?$data['HezuoRxcqKey_3']:0;
    $html = "<h1>热血传奇活动后台:</h1>";
    echo $html;
    $sort = 1;
    if(!empty($newdata)){
        $html1 = "<table border='1' class='bt1' style='width: 400px;'><tr><td colspan='5' align='center'><h3>投票结果</h3></td></tr><tr><td align='center'>序号</td><td align='center'>票数</td><td align='center'></td><td align='center'>操作</td></td></tr>";
        foreach($newdata as $k=>$row){
            $html1.="<tr><td align='center'>".$sort."</td><td align='center'>".$row."</td><td align='center'><input type='text'style='width: 30px;' id=$sort></td><td align='center'><input type='button'style='width: 40px;' value='修改'  onclick='uplNum($sort)'></td></tr>";
            $sort++;
        }
    }
    echo $html1;
}
?>

<a href="voteAdmin.php">返回</a>
</br>
<script src="js/jquery.min.js"></script>
<script>
    //后台设置投票数
    function uplNum(id){
        var num = $("#"+id).val();
        num = parseInt(num)
        if(!isNaN(num) && num>=0){
            var flag = "joymevote";
            jQuery.ajax({
                "url": "updateVotesNum.php",
                "type": "post",
                "data": {"id":id,"num":num,"flag":flag},
                "success": function(msg) {
                    var data = eval('(' + msg + ')');
                    if(data['rs']==0){
                        alert("修改成功");
                        location.reload();
                    }else{
                        location.reload();
                    }
                }
            })
        }else{
            alert("输入数据有误");
            return false;
        }
    };
</script>
