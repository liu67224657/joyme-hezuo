<?php
//    @$flag = $_GET['flag'];
//    print_r($flag);
//    die;
//    if(!$flag || $flag!='joymere'){
//        $url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
//        $arr = explode("success",$url);
//        $newurl = $arr[0]."input.php";
//        header("location:$newurl");
//    }
?>
<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta content="width=device.width, initial-scale=1.0, user-scalable=no" name="viewport">
	<meta content="yes" name="apple-mobile-web-app-capable">
	<meta content="black" name="apple-mobile-web-app-status-bar-style">
	<meta content="telephone=no" name="format-detection">
	<title>硕美科+着迷玩霸 刀塔传奇独占礼包 </title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="wrapper">
	<div class="banner">
		<span><img src="images/bg-1.jpg" alt=""></span>
		<span><img src="images/bg-2.jpg" alt=""></span>
		<span><img src="images/bg-3.jpg" alt=""></span>
		<span><img src="images/bg-8.jpg" alt=""></span>
		<span><img src="images/bg-7.jpg" alt=""></span>
	</div>
	<div class="win">
		<h1>恭喜你！</h1>
		<h2>你获得的礼包号码是</h2>
		<div class="butten">
			<span class="btn show"><?php echo $_GET['suc_code']?></span>
		</div>
		<h3></h3>
	</div>
	<div class="text">
		<h1><span>礼包兑换方法:</span></h1>
		<p>进入游戏，点击左上角头像;在弹框内点击“输入兑换码”按钮正确输入礼品码；获取礼包内物品。</p>
	</div>
	<div class="butten">
		<a href="http://www.joyme.com/wiki/dtcq/index.shtml" class="btn go">去WIKI看攻略</a>
	</div>
</div>
</body>
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
	$(window).resize(function() {
      var h=$(this).height();
      $('#wrapper').height(h)
    });
</script>
</html>