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
    $url = !empty($_POST['url'])?$_POST['url']:'';
    $exp = !empty($_POST['exp'])?$_POST['exp']:'';

    if(empty($id) || empty($url)){
        $data = array("rs"=>3,'msg'=>'ParamError');
    }else{
        if($id == 1){
            $key = 'HezuoRxcqUrlKey_1';
        }elseif($id == 2){
            $key = 'HezuoRxcqUrlKey_2';
        }elseif($id == 3){
            $key = 'HezuoRxcqUrlKey_3';
        }elseif($id == 4){
            $key = 'HezuoRxcqUrlKey_4';
        }elseif($id == 5){
            $key = 'HezuoRxcqUrlKey_5';
        }elseif($id == 6){
            $key = 'HezuoRxcqUrlKey_6';
        }
        $content = $url.'@'.$exp;
        if($memcach->get($key)){
            if($memcach->replace($key,$content)){
                $result = true;
            }
        }else{
            if($memcach->add($key,$content)){
                $result = true;
            }
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
    $data = $memcach->get(array('HezuoRxcqUrlKey_1','HezuoRxcqUrlKey_2','HezuoRxcqUrlKey_3','HezuoRxcqUrlKey_4','HezuoRxcqUrlKey_5','HezuoRxcqUrlKey_6'));
    $newdata['HezuoRxcqUrlKey_1'] = !empty($data['HezuoRxcqUrlKey_1'])?$data['HezuoRxcqUrlKey_1']:'images/img1.jpg';
    $newdata['HezuoRxcqUrlKey_2'] = !empty($data['HezuoRxcqUrlKey_2'])?$data['HezuoRxcqUrlKey_2']:'images/img2.jpg';
    $newdata['HezuoRxcqUrlKey_3'] = !empty($data['HezuoRxcqUrlKey_3'])?$data['HezuoRxcqUrlKey_3']:'images/img3.jpg';
    $newdata['HezuoRxcqUrlKey_4'] = !empty($data['HezuoRxcqUrlKey_4'])?$data['HezuoRxcqUrlKey_4']:'images/img4.jpg';
    $newdata['HezuoRxcqUrlKey_5'] = !empty($data['HezuoRxcqUrlKey_5'])?$data['HezuoRxcqUrlKey_5']:'images/img5.jpg';
    $newdata['HezuoRxcqUrlKey_6'] = !empty($data['HezuoRxcqUrlKey_6'])?$data['HezuoRxcqUrlKey_6']:'images/img6.jpg';

    $html = "<h1>热血传奇活动后台:</h1>";
    echo $html;
    $sort = 1;
    if(!empty($newdata)){
        $html1 = "<table border='1' class='bt1' style='width: 400px;'><tr><td colspan='6' align='center'><h3>设置图片路径</h3></td></tr><tr><td align='center'>序号</td><td align='center'>图片</td><td align='center'>当前连接</td><td align='center'>设置新图</td><td align='center'>设置标题</td><td align='center'>操作</td></td></tr>";
        foreach($newdata as $k=>$row){
            $arr = explode('@',$row);
            $exp = !empty($arr[1])?$arr[1]:'';
            $html1.="<tr><td align='center'>".$sort."</td><td align='center'><img style='width:100px;height:100px;' src='$arr[0]'></td><td align='center'><input type='text' value='$arr[0]'></td><td align='center'><input type='text' id=$sort></td><td align='center'><input type='text' class=$sort value='$exp'></td><td align='center'><input type='button'style='width: 40px;' value='设置'  onclick='uplNum($sort)'></td></tr>";
            $sort++;
        }
    }
    echo $html1;
}
?>
<a href="voteAdmin.php">返回</a>
</br>
<!--<span style="color: red;">图片地址请使用http://格式</span>-->
</br>
<script src="js/jquery.min.js"></script>
<script>
    //后台设置投票数
    function uplNum(id){
        var url = $("#"+id).val();
        var explain = $("."+id).val();
        if(url){
            var flag = "joymevote";
            jQuery.ajax({
                "url": "updateImages.php",
                "type": "post",
                "data": {"id":id,"url":url,"flag":flag,'exp':explain},
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
            alert("链接不能为空");
            return false;
        }
    };
</script>
