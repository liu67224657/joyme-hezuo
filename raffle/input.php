<?php
//接受处理序列号
include("codelist.php");
@$code = @trim($_POST['code']);
if($code){
    $flag = false;
    foreach($codelist as $k=>$v){
        if($k==$code){
            $num = $v;
            $flag = true;
        }
    }
    if($flag){
        //输入序列号正确，做文件的日志记录
        ob_start();
        $file = @fopen("Raffle_logo.txt","a");
        $file = @fopen("_logo.txt","a");
        fwrite($file, date("Y-m-d H:i:s",time())."序列号为：".$code."领取成功,礼包号为:".$v."|\r\n");
        fclose($fp);//写入成功，关闭文件
        ob_clean();
        echo $num;die;
    }else{
        echo 'Not';die;
    }
}
//跳转链接
$arr = explode("input",'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);
$newurl = $arr[0]."success.php";
?>
<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta content="width=device.width, initial-scale=1.0, user-scalable=no" name="viewport">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <title>硕美科+着迷玩霸 刀塔传奇独占礼包</title>
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript">
        window.addEventListener('DOMContentLoaded', function (){
            document.addEventListener('touchstart', function (){return false}, true)
        }, true);
    </script>
</head>
<body>
<div id="wrapper">
    <div class="banner">
        <span><img src="images/bg-1.jpg" alt=""></span>
        <span><img src="images/bg-2.jpg" alt=""></span>
        <span><img src="images/bg-3.jpg" alt=""></span>
        <span><img src="images/bg-4.jpg" alt=""></span>
        <span><img src="images/bg-6.jpg" alt=""></span>
    </div>
    <div class="title">
        <cite class="fl"><img src="images/icon3.png" alt=""></cite>
        <span>着迷玩霸独家发放！ </span>
    </div>

    <div class="butten">
        <span class="btn input_box"><input type="text" id="code" value="输入序列号"></span>
        <input type="hidden" id="url" value="<?php echo $url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];?>">
        <input type="hidden" id="newurl" value="<?php echo $newurl;?>">
        <a href="#" class="btn exchange" id="sub">立即兑换</a>
    </div>
    <div class="dialog"><p>对不起您输入的有误</p></div>

    <div class="text">
        <h1><span>礼包兑换方式:</span></h1>
        <p>输入硕美科耳机包装上的兑换码，即可获得价值100元的《刀塔传奇》独占礼包！钻石128、金币12888、经验药膏x4、虚空尘埃x3、黄金电能核心x2、猴子灵魂石x1</p>
    </div>
    </br>
    </br>
    </br>
    </br>
</div>
</body>
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript">

    $(window).resize(function() {
        var h=$(this).height();
        $('#wrapper').height(h)
    });

    //验证输入的序列号是否正确
    $("#sub").click(function(){

        //获取输入的序列号
        var code = $("#code").val();
        var url = $("#url").val();
        var sucurl = $("#newurl").val();
        var newurl = sucurl+"?flag=joymere&suc_code=";
        if(code!='输入序列号'&& code!='输入正确序列号' && code!=""){
            if(code.match(/[a-zA-Z0-9_]/g)){
                jQuery.ajax({
                    url: "input.php",
                    type:"post",
                    async: false,
                    data: "code="+code,
                    success: function(msg) {
                        if(msg=='Not'){
                            Prompt("序列号输入错误","输入正确序列号");
                            return false;
                        }else{
                            window.location.href=newurl+msg;
                        }
                    }
                })

            }else{
                Prompt("序列号输入错误","输入序列号");
                return false;
            }
        }else{
            Prompt("序列号不能为空","输入序列号");
            return false;
        }
    })

    $('input[type=text]').focus(function(){
        if(($(this).val() && $(this).val()=='输入序列号') || ($(this).val() && $(this).val()=='输入正确序列号')){
            $(this).val('')
        }
    })

    $('input[type=text]').blur(function(){
        if($(this).val()==""){
            $(this).val('输入序列号')
        }
    })

    //显示提示信息
    function Prompt(str,val){
        $('.dialog').addClass('close')
        $('.dialog p').text(str);
        var timer=null;
        $("#code").val(val);
        timer=setTimeout(function(){
            $('.dialog').removeClass('close')
            clearTimeout(timer);
        },3000)
    }
</script>
</html>

