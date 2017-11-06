<?php
    header("Content-type: text/html; charset=utf-8");
    $forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);
    if(!preg_match("/mobile/",$forasp)){
        die("只能通过手机端访问！");
    }
    //设置开始投票时间
    $filename = "beginTime.txt";
    $file = @fopen($filename,"r");
    $line_votes=@fgets($file); /*读出已经记录的投票结果*/
    if(!empty($line_votes)){
        $begin = $line_votes;
    }else{
        $begin = 1427547600;
    }
?>
<!DOCTYPE HTML>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection">
<meta name="Keywords" content="">
<meta name="Description" content="">
<link rel="stylesheet" type="text/css" href="css/common.css">
<script src="js/jquery.min.js"></script>
<script> 
$(function(){
	$('.vote-but').click(function(){
		$('#wind').show();
	});	
	$('#show').click(function(){
		$('#wind').hide();
	});
});
</script>
<title>2015着迷公司年会节目投票</title>
</head>
<body>	
	<div id="wind" style="display:none;">
        <div class="window">
            <a id="show" href="">×</a>
            <div id="tishi" class="wind-icon-fail"></div>
            <div class="wind-text"></div>
        </div>
    </div>
	<div class="main">
    	<div class="top">
        	<img src="img/banner.jpg">
        </div>
        <div class="menu-tit">
			<img src="img/menu_tit.gif">
        </div>
        <div class="rule">
        <?php if(time()<$begin):?>
            <?php echo "  开始时间：".date("Y-m-d H:i:s",$begin);?>
        <?php else:?>
                投票规则：一人一票制
        <?php endif;?>
        </div>
        <div class="menu-list">
            <div class="show-list1">
            	<div class="show">
                	<div class="show-main">
                        <img src="img/zpimg/zl.jpg">
                        <i>1号</i>
                        <div class="show-tit">自恋青春</div>
                    </div>
                    <div class="vote">
                        <?php if(time()>$begin):?>
                            <a class="vote-but" onclick="vote(1)">
                        <?php else:?>
                            <a class="vote-but" onclick="stop()">
                        <?php endif;?>
                            </a>
                    </div>
                </div>
            	<div class="show">
                	<div class="show-main">
                        <img src="img/zpimg/super.jpg">
                        <i>3号</i>
                        <div class="show-tit">Super Power Origin</div>
                    </div>
                    <div class="vote">
                        <?php if(time()>$begin):?>
                            <a class="vote-but" onclick="vote(3)">
                        <?php else:?>
                            <a class="vote-but" onclick="stop()">
                        <?php endif;?>
                        </a>
                    </div>
                </div>
            	<div class="show">
                	<div class="show-main">
                        <img src="img/zpimg/zc.jpg">
                        <i>5号</i>
                        <div class="show-tit">最初的梦想</div>
                    </div>
                    <div class="vote">
                        <?php if(time()>$begin):?>
                            <a class="vote-but" onclick="vote(5)">
                        <?php else:?>
                            <a class="vote-but" onclick="stop()">
                        <?php endif;?>
                        </a>
                    </div>
                </div>
            	<div class="show">
                	<div class="show-main">
                        <img src="img/zpimg/md.jpg">
                        <i>7号</i>
                        <div class="show-tit">撕大萌捕</div>
                    </div>
                    <div class="vote">
                        <?php if(time()>$begin):?>
                            <a class="vote-but" onclick="vote(7)">
                        <?php else:?>
                            <a class="vote-but" onclick="stop()">
                        <?php endif;?>
                            </a>
                    </div>
                </div>                
                <br class="c"/>
            </div>
            <div class="show-list2">
            	<div class="show">
                	<div class="show-main">
                        <img src="img/shw2.jpg">
                        <i>2号</i>
                        <div class="show-tit">扒马褂</div>
                    </div>
                    <div class="vote">
                        <?php if(time()>$begin):?>
                            <a class="vote-but" onclick="vote(2)">
                        <?php else:?>
                            <a class="vote-but" onclick="stop()">
                        <?php endif;?>
                            </a>
                    </div>
                </div>
            	<div class="show">
                	<div class="show-main">
                        <img src="img/zpimg/rc.jpg">
                        <i>4号</i>
                        <div class="show-tit">日常狂想曲</div>
                    </div>
                    <div class="vote">
                        <?php if(time()>$begin):?>
                            <a class="vote-but" onclick="vote(4)">
                        <?php else:?>
                            <a class="vote-but" onclick="stop()">
                        <?php endif;?>
                            </a>
                    </div>
                </div>
            	<div class="show">
                	<div class="show-main">
                        <img src="img/zpimg/ks.jpg">
                        <i>6号</i>
                        <div class="show-tit">快闪</div>
                    </div>
                    <div class="vote">
                        <?php if(time()>$begin):?>
                            <a class="vote-but" onclick="vote(6)">
                        <?php else:?>
                            <a class="vote-but" onclick="stop()">
                        <?php endif;?>
                            </a>
                    </div>
                </div>
            	<div class="show">
                	<div class="show-main">
                        <img src="img/zpimg/sj.jpg">
                        <i>8号</i>
                        <div class="show-tit">世界游戏史</div>
                    </div>
                    <div class="vote">
                        <?php if(time()>$begin):?>
                            <a class="vote-but" onclick="vote(8)">
                        <?php else:?>
                            <a class="vote-but" onclick="stop()">
                        <?php endif;?>
                            </a>
                    </div>
                </div>
            </div>            
            <br class="c"/>
        </div>
        <div class="footer"></div> 
    </div>
</body>
</html>
<script>
    function vote(id){
        var flag = "joymenhtp";
        var str = "";
        if(id){
            jQuery.ajax({
                "url": "vote.php",
                "type": "post",
                "data": {"vote_code":id,"flag":flag},
                "success": function(msg) {
                    if(msg=="NO"){
                      str = "您已经投过票啦，不能重复投票！";
                    }else if(msg=="DANGER"){
                      str = "已阻止非法投票！";
                    }else{
                      str = "恭喜您 投票成功！";
                        var div = document.getElementById('tishi');
                        div.setAttribute("class", "wind-icon-successful");
                    }
                    $('.windbg').show();
                    $('.wind-text').text(str);
                    $('#wind').show();
                }
            })
        }else{
            $('.windbg').show();
            $('.wind-text').text("您还没有选择节目！");
            $('#wind').show();
        }
    }

    function stop(){
        $('.windbg').show();
        $('.wind-text').text("投票时间还未开始");
        $('#wind').show();
    }
</script>
