<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /> 
<meta name="Keywords" content="热血传奇8月7日-8月9日限时不限号活动专题" />
<meta name="description" content="热血传奇8月7日-8月9日限时不限号活动专题" />
<meta name="360-site-verification" content="1de1c482f5c029917d9e65306dcb78d4" /> 
<meta name="sogou_site_verification" content="VEFkoCjIwA" /> 
<title>热血传奇8月7日-8月9日限时不限号活动专题</title>
<link href="style.css" rel="stylesheet" type="text/css">
<link href="http://lib.joyme.com/static/theme/default/css/header_simple.css" rel="stylesheet" type="text/css"/>
</head>
<?php
$forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);
if(preg_match("/mobile/",$forasp)){
    die("只能通过PC端访问！");
}

include_once("config/config.php");
$memcach = new memcache();
$memcach->connect($config['mem_host'],$config['mem_port']);

$data = $memcach->get(array('HezuoRxcqKey_1','HezuoRxcqKey_2','HezuoRxcqKey_3','HezuoRxcqUrlKey_1','HezuoRxcqUrlKey_2','HezuoRxcqUrlKey_3','HezuoRxcqUrlKey_4','HezuoRxcqUrlKey_5','HezuoRxcqUrlKey_6'));
$votadata['HezuoRxcqKey_1'] = !empty($data['HezuoRxcqKey_1'])?$data['HezuoRxcqKey_1']:0;
$votadata['HezuoRxcqKey_2'] = !empty($data['HezuoRxcqKey_2'])?$data['HezuoRxcqKey_2']:0;
$votadata['HezuoRxcqKey_3'] = !empty($data['HezuoRxcqKey_3'])?$data['HezuoRxcqKey_3']:0;

$newdata['HezuoRxcqUrlKey_1'] = !empty($data['HezuoRxcqUrlKey_1'])?$data['HezuoRxcqUrlKey_1']:'images/img1.jpg';
$newdata['HezuoRxcqUrlKey_2'] = !empty($data['HezuoRxcqUrlKey_2'])?$data['HezuoRxcqUrlKey_2']:'images/img2.jpg';
$newdata['HezuoRxcqUrlKey_3'] = !empty($data['HezuoRxcqUrlKey_3'])?$data['HezuoRxcqUrlKey_3']:'images/img3.jpg';
$newdata['HezuoRxcqUrlKey_4'] = !empty($data['HezuoRxcqUrlKey_4'])?$data['HezuoRxcqUrlKey_4']:'images/img4.jpg';
$newdata['HezuoRxcqUrlKey_5'] = !empty($data['HezuoRxcqUrlKey_5'])?$data['HezuoRxcqUrlKey_5']:'images/img5.jpg';
$newdata['HezuoRxcqUrlKey_6'] = !empty($data['HezuoRxcqUrlKey_6'])?$data['HezuoRxcqUrlKey_6']:'images/img6.jpg';

$memcach->close();
?>
<body>
<!--topbar-->
<div id="joyme-head-2015">
    <div class="joyme-head-nav">
        <div class="joyme-left-nav">
            <a href="http://www.joyme.com">返回着迷首页 >></a>
            <a href="http://www.joyme.com/news/official/">手游资讯</a>
            <a href="http://v.joyme.com/">着迷视频</a>
            <a href="http://wiki.joyme.com/">着迷WIKI</a>
            <a href="http://www.joyme.com/giftmarket">礼包中心</a>
            <a href="http://bbs.joyme.com">论 坛</a>
            <a href="http://html.joyme.com/mobile/gameguides.html">应用下载</a>
        </div>
    <script>
     document.write(unescape("%3Cscript src='http://passport.joyme.com/auth/header/userinfo?t=simple%26v=" + Math.random() + "' type='text/javascript'%3E%3C/script%3E"));
    </script>
    </div>
</div> 
<!-- topbar-end -->
<div class="cen-bg">
<div id="t_area" >

</div>
	<div class="link">
    	<a href="http://mir.qq.com/" class="gw-but"></a>
    </div>
    <div class="cen-main">  
    	<!--cen-time-->
    	<div class="cen-time">
        	<em>不限号测试结束还有</em>
        	<div class="time"><span id="LeftTime"></span></div>
            <span>8月7日-8月9日  72小时限时不限号</span>
        </div>        
    	<!--cen-time end-->
    	<!--cen-clumn one-->
        <div class="cen-clumn one">
        	<h2>本次测试，你将会选择哪个职业进行游戏？赶紧来说出你的选择。</h2>
            <div class="job-list mr">            	
                <cite>
                    <img src="images/soldier.png">                
                    <dl class="list-infor">
                        <dt>战士</dt>
                        <dd>已有<em id="1"><?=$votadata['HezuoRxcqKey_1']?></em>人投票</dd>
                    </dl>
                </cite>
                <span class="vote-but" onClick="showarea(1);clearTimeout(timer);return false;" onMouseOut="timer=setTimeout('offarea()',1000);"></span>
            </div>
            <div class="job-list mr">
            	<cite>
                	<img src="images/master.png">                                
                    <dl class="list-infor">
                        <dt>法师</dt>
                        <dd>已有<em id="2"><?=$votadata['HezuoRxcqKey_2']?></em>人投票</dd>
                    </dl>
                </cite>
                <span class="vote-but" onClick="showarea(2);clearTimeout(timer);return false;"  onMouseOut="timer=setTimeout('offarea()',1000);"></span>
            </div>
            <div class="job-list">
            	<cite>
                    <img src="images/priest.png">                
                    <dl class="list-infor">
                        <dt>道士</dt>
                        <dd>已有<em id="3"><?=$votadata['HezuoRxcqKey_3']?></em>人投票</dd>
                    </dl>
                </cite>
                <span class="vote-but" onClick="showarea(3);clearTimeout(timer);return false;" onMouseOut="timer=setTimeout('offarea()',1000);"></span>
            </div>
        <div class="clear"></div>
        </div>
    	<!--cen-clumn one end-->
    	<!--cen-clumn two-->
        <div class="cen-clumn two">
        	<h2>即日起，上传游戏截图就有机会赢得海量Q币。还等什么，还不快来参加!</h2>
            <div class="active-infor">
            	<h3>活动说明：</h3>
                <li><span>1</span>点击上传截图按钮，至特定页面上传你游戏中的截图，即可参与活动。</li>
                <li><span>2</span> 优秀的游戏截图将获得丰硕的Q币奖励。</li>
                <a class="upload"  target="_blank" href="http://bbs.joyme.com/thread-21868-1-1.html"></a>
            </div>
            <div class="screenshot">
            	<div class="screenshot-list mr">
                    <?php $arr = explode('@',$newdata['HezuoRxcqUrlKey_1'])?>
                	<cite><img src="<?=$arr[0]?>" onerror="this.src='images/img1.jpg'"></cite>
                    <span><?php echo !empty($arr[1])?$arr[1]:''?></span>
                </div>
            	<div class="screenshot-list mr">
                    <?php $arr = explode('@',$newdata['HezuoRxcqUrlKey_2'])?>
                	<cite><img src="<?=$arr[0]?>" onerror="this.src='images/img2.jpg'"></cite>
                    <span><?php echo !empty($arr[1])?$arr[1]:''?></span>
                </div>
            	<div class="screenshot-list">
                    <?php $arr = explode('@',$newdata['HezuoRxcqUrlKey_3'])?>
                    <cite><img src="<?=$arr[0]?>" onerror="this.src='images/img3.jpg'"></cite>
                    <span><?php echo !empty($arr[1])?$arr[1]:''?></span>
                </div>
            	<div class="screenshot-list mr">
                    <?php $arr = explode('@',$newdata['HezuoRxcqUrlKey_4'])?>
                    <cite><img src="<?=$arr[0]?>" onerror="this.src='images/img4.jpg'"></cite>
                    <span><?php echo !empty($arr[1])?$arr[1]:''?></span>
                </div>
            	<div class="screenshot-list mr">
                    <?php $arr = explode('@',$newdata['HezuoRxcqUrlKey_5'])?>
                    <cite><img src="<?=$arr[0]?>" onerror="this.src='images/img5.jpg'"></cite>
                    <span><?php echo !empty($arr[1])?$arr[1]:''?></span>
                </div>
            	<div class="screenshot-list">
                    <?php $arr = explode('@',$newdata['HezuoRxcqUrlKey_6'])?>
                    <cite><img src="<?=$arr[0]?>" onerror="this.src='images/img6.jpg'"></cite>
                    <span><?php echo !empty($arr[1])?$arr[1]:''?></span>
                </div>
            <div class="clear"></div>
            </div>
        <div class="clear"></div>
        </div>
    	<!--cen-clumn two end-->
        <div class="clear"></div>
    </div>
    <div class="wp-bg">
        <span class="bg-1"></span>
        <span class="bg-2"></span>
        <span class="bg-3"></span>
        <span class="bg-4"></span>
        <span class="bg-5"></span>
        <span class="bg-6"></span>
        <span class="bg-7"></span>
        <span class="bg-8"></span>
        <span class="bg-9"></span>
        <span class="bg-10"></span>
        <span class="bg-11"></span>
        <span class="bg-12"></span>
        <span class="bg-13"></span>
        <span class="bg-14"></span>
        <span class="bg-15"></span>
        <span class="bg-16"></span>
        <span class="bg-17"></span>
        <span class="bg-18"></span>
        <span class="bg-19"></span>
        <span class="bg-20"></span>
    </div>
</div>
<!--footer-->
<div id="footer_bottom"> 
    <span>© 2011－2015 joyme.com, all rights reserved</span> 
    <a href="http://www.joyme.com/help/aboutus" target="_blank" rel="nofollow">关于着迷</a> | 
    <a href="http://www.joyme.com/about/job/zhaopin" target="_blank" rel="nofollow">工作在着迷</a> | 
    <a href="http://www.joyme.com/about/contactus" target="_blank" rel="nofollow">商务合作</a>| 
    <a href="http://www.joyme.com/help/law/parentsprotect/" target="_blank" rel="nofollow">家长监护</a>| 
    <a href="http://www.joyme.com/sitemap.htm" target="_blank">网站地图</a> 
    <span><a href="http://www.miibeian.gov.cn/" target="_blank">京ICP备11029291号</a></span> 
    <span>京公网安备110108001706号</span> 
</div>
<!--footer end-->
<script src="js/jquery.min.js"></script>
<script src="js/vote.js"></script>
</body>
</html>