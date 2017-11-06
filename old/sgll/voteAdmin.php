<?php
/**
 * Created by PhpStorm.
 * User: xinshi
 * Date: 2015/6/19
 * Time: 17:04
 */
header("Content-type:text/html;charset=utf-8");

$forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);
if(preg_match("/mobile/",$forasp)){
    die("只能通过PC端访问！");
}

include_once("voteModel.class.php");
$model = new voteModel();

//如果投票成功
$flag = !empty($_POST['flag'])?$_POST['flag']:'';

if($_POST && $flag=='joymevote'){

    $id = !empty($_POST['id'])?$_POST['id']:'';
    $num = is_numeric($_POST['num'])?$_POST['num']:'';

    if($model->updateSgllDate($num,$id)){
        $data = array("rs"=>0,'msg'=>'修改成功');
    }else{
        $data = array("rs"=>1,'msg'=>'修改失败');
    }
    echo json_encode($data);
    exit;
}else{

    $html = "<h1 style='color: red;'>三国来了投票后台:</h1>";
    echo $html;
    $result = $model->selectSgllInfo();
    if(!empty($result)){
        $html1 = "<table border='1' class='bt1' style='width: 400px;'><tr><td colspan='5' align='center'><h3>作品投票结果</h3></td></tr><tr><td align='center'>编号</td><td align='center'>投票数量</td><td align='center'>修改为</td><td align='center'>操作</td></td></tr>";
        foreach($result as $k=>$row){
            $id = $row['id'];
            $html1.="<tr><td align='center'>".$id."</td><td align='center'>".$row['vote_num']."</td><td align='center'><input type='text'style='width: 30px;' id=$id></td><td align='center'><input type='button'style='width: 40px;' value='修改'  onclick='uplNum($id)'></td></tr>";
        }
    }
    echo $html1;
}
?>

<script src="js/jquery.min.js"></script>
<script>

        function uplNum(id){
            var num = $("#"+id).val();
            if(!isNaN(num) && num>=0){
                var flag = "joymevote";
                jQuery.ajax({
                    "url": "voteAdmin.php",
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

