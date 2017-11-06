<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="Keywords" content="端午节×父亲节活动专题页" />
    <meta name="description" content="端午节×父亲节活动专题页" />
    <meta name="360-site-verification" content="1de1c482f5c029917d9e65306dcb78d4" />
    <meta name="sogou_site_verification" content="VEFkoCjIwA" />
    <title>三国来了专题</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link href="http://lib.joyme.com/static/theme/default/css/header_simple.css" rel="stylesheet" type="text/css"/>
</head>
<?php
$forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);
if(preg_match("/mobile/",$forasp)){
    die("只能通过PC端访问！");
}

include_once("voteModel.class.php");

$model = new voteModel();
$result = $model->selectSgllInfo();
foreach($result as $k=>$v){
    $data[] = $v['vote_num'];
}
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
    <!--弹窗-->
    <!--感谢参与投票，填写信息参与抽奖 弹窗-->
    <div id="wind" style="display:none;">
        <div class="vote-wind">
            <h2><img src="images/wind-titlecont.png"/><a id="show"><img src="images/close.png"/></a></h2>
            <div class="vote-wind-cont">
                <div class="item">
                    <span class="label"><img src="images/name.png"/></span>
                    <div class="fl"><input type="text" id="username" name="mail" class="text" tabindex="4" sta="0"  onblur="checkContent(1)"><em id="tip_name"></em></div>
                    <br class="clear"/>
                </div>
                <div class="item">
                    <span class="label"><img src="images/tel.png"/></span>
                    <div class="fl"><input type="text" id="phone" name="mail" class="text" tabindex="4" sta="0" onblur="checkContent(2)"><em id="tip_phone"></em></div>
                    <br class="clear"/>
                </div>
                <div class="item">
                    <span class="label"><img src="images/qq.png"/></span>
                    <div class="fl"><input type="text" id="qq" name="mail" class="text" tabindex="4" sta="0"  onblur="checkContent(3)"><em id="tip_qq"></em></div>
                    <br class="clear"/>
                </div>
                <div class="item">
                    <span class="label"><img src="images/adr.png"/></span>
                    <div class="fl"><textarea rows="2" id="address" onblur="checkContent(4)"></textarea><em id="tip_address" ></em></div>
                    <br class="clear"/>
                </div>
                <div class="item">
                    <a class="sub"></a>
                </div>
                <br class="clear"/>
            </div>
        </div>
    </div>
    <!--提交成功  弹窗-->
    <div id="wind-successful" style="display:none;">
        <div class="vote-wind-successful">
            <h2></h2>
            <div class="vote-wind-cont">
                <div class="item">提交成功</div>
                <div class="item">
                    <a id="show2" class="close item_close"></a>
                </div>
            </div>
        </div>
    </div>
    <!--今天投票已达上限，请明天再试  弹窗-->
    <div id="wind" class="wind"  style="display:none;">
        <div class="vote-wind-limit">
            <h2></h2>
            <div class="vote-wind-cont">
                <div class="item" id="tip_xianzhi"></div>
                <div class="item">
                    <a id="show" class="close item_close"></a>
                </div>
            </div>
        </div>
    </div>
    <!--视屏播放弹窗-->
    <div id="video-wind" style="display:none;">
        <div class="video-main">
            <i id="video-show"></i>
            <embed wmode="window" flashvars="vid=v0157ni0n5c&amp;tpid=3&amp;showend=1&amp;showcfg=1&amp;searchbar=1&amp;shownext=1&amp;list=2&amp;autoplay=1&amp;outhost=http%3A%2F%2Fv.qq.com%2Fpage%2Fv%2F5%2Fc%2Fv0157ni0n5c.html&amp;openbc=0&amp;title=%20%E4%B8%89%E5%9B%BD%E6%9D%A5%E4%BA%86%E5%A5%B3%E7%A5%9E%E7%94%B5%E7%AB%9E%E8%AE%AD%E7%BB%83%E8%90%A5" src="http://imgcache.qq.com/tencentvideo_v1/player/TencentPlayer.swf?max_age=86400&amp;v=20140714" quality="high" name="tenvideo_flash_player_1435050973953" id="tenvideo_flash_player_1435050973953" bgcolor="#000000" width="858px" height="503px" align="middle" allowscriptaccess="always" allowfullscreen="true" type="application/x-shockwave-flash" pluginspage="http://get.adobe.com/cn/flashplayer/">
        </div>
    </div>
    <!--弹窗 end-->
    <div class="cen-main">
        <div class="main-clumn">
            <div class="ns-but"><a href="http://sanguo.redatoms.com/txdy/"></a></div>
            <div><img src="images/erweima.png" alt="二维码" title="二维码"/></div>
        </div>
        <!---->
        <div class="main-clumn">
            <div class="video">
                <cite><img src="images/ssx-img.png"/></cite>
                <div class="video-but"><img src="images/video-but.png"/></div>
                <div class="video-img"><div></div><img src="images/viedo-img.jpg"/></div>
                <i class="right-top"></i>
                <i class="right-bottom"></i>
                <i class="left-bottom"></i>
                <i class="left-top"></i>
            </div>
            <div class="news">
                <h2><!---<a href="">更多+</a>--></h2>
                <div class="news-cont">
                    <li><a href="http://sanguo.redatoms.com/news/158.shtml">全新资料片 “天下第一”上线期间活动汇总！</a><span>06-18</span></li>
                    <li><a href="http://sanguo.redatoms.com/txdy/">第一届女神电竞训练营火爆开启</a><span>06-18</span></li>
                    <li><a>敬请期待!</a></li>
                    <li><a>敬请期待!</a></li>
                    <li><a>敬请期待!</a></li>
                    <li><a>敬请期待!</a></li>
                    <i class="right-bottom"></i>
                    <i class="left-bottom"></i>
                </div>
            </div>
            <br class="clear"/>
        </div>
        <!---->
        <div class="main-clumn">
            <div class="vote">
                <h2><img src="images/toupiao-title.png"/></h2>
                <div class="vote-cont">
                    <h3>女神电竞训练营</h3>
                    <div class="vote-cont-list">
                        <h5><span>1.　6月23日~6月28日</span></h5>
                        <li>玩家前往minisite为女神进行投票，从12名女神中选出3名你最支持的女神，投票完成后可参加1次抽奖，我们将选取票数最高的8名女神参加电竞训练营活动</li>
                    </div>
                    <div class="vote-cont-list">
                        <h5><span>2.　6月23日~6月24日</span></h5>
                        <li>玩家前往minisite报名参选女神电竞训练营导师，根据页面提示留下相应信息（游戏昵称、游戏id、所在势力、所在城市、导师宣言等）</li>
                    </div>
                    <div class="vote-cont-list">
                        <h5><span>3.　6月25日~6月28日</span></h5>
                        <li>玩家前往minisite为导师投票，同时论坛将开启导师竞猜活动，猜中最终导师人选的玩家将获得礼包奖励</li>
                    </div>
                    <div class="vote-cont-list">
                        <h5><span>4.　6月29日</span></h5>
                        <li>我们将公布最终的投票胜出的女神及玩家导师结果，同时公布电竞训练营分队情况</li>
                    </div>
                    <div class="vote-cont-list">
                        <h5><span>5.　7月1日~7月3日</span></h5>
                        <li>女神及玩家导师将在北京参加为期三天的秘密电竞训练</li>
                    </div>
                    <div class="vote-cont-list">
                        <h5><span>6.　7月3日</span></h5>
                        <li>将在“斗鱼”和“KK直播”进行“天下第一”电竞比赛直播，可在当天至论坛参加电竞比赛竞猜活动，竞猜最终获胜女神正确可获得礼包奖励</li>
                    </div>
                    <div class="people-list">
                        <div class="people-cont mr">
                            <i class="people-left-top"></i>
                            <i class="people-right-top"></i>
                            <i class="people-right-bottom"></i>
                            <i class="people-left-bottom"></i>
                            <cote class="rq-icon"></cote>
                            <cite><img src="images/people-1.jpg"/>
                                <div class="people-intro">模特、主持、演员，出演《樱熊不联盟》《男宅突袭》等节目</div></cite>
                            <div class="people-name"><span>曹安娜：</span><em id="1"><?=$data[0]?></em></div>
                            <div class="vote-but"><a onclick="voteButFun(1)"></a></div>
                        </div>
                        <div class="people-cont mr">
                            <i class="people-left-top"></i>
                            <i class="people-right-top"></i>
                            <i class="people-right-bottom"></i>
                            <i class="people-left-bottom"></i>
                            <cote class="tm-icon"></cote>
                            <cite><img src="images/people-2.jpg"/>
                                <div class="people-intro">人气showgirl</div></cite>
                            <div class="people-name"><span>曹丽敏：</span><em id="2"><?=$data[1]?></em></div>
                            <div class="vote-but"><a onclick="voteButFun(2)"></a></div>
                        </div>
                        <div class="people-cont mr">
                            <i class="people-left-top"></i>
                            <i class="people-right-top"></i>
                            <i class="people-right-bottom"></i>
                            <i class="people-left-bottom"></i>
                            <cote class="mv-icon"></cote>
                            <cite><img src="images/people-3.jpg"/>
                                <div class="people-intro">甜心萌妹 热爱游戏</div></cite>
                            <div class="people-name"><span>妮可：</span><em id="3"><?=$data[2]?></em></div>
                            <div class="vote-but"><a onclick="voteButFun(3)"></a></div>
                        </div>
                        <div class="people-cont">
                            <i class="people-left-top"></i>
                            <i class="people-right-top"></i>
                            <i class="people-right-bottom"></i>
                            <i class="people-left-bottom"></i>
                            <cote class="mv-icon"></cote>
                            <cite><img src="images/people-4.jpg"/>
                                <div class="people-intro">喜欢卡牌、喜爱游戏</div></cite>
                            <div class="people-name"><span>妮妮：</span><em id="4"><?=$data[3]?></em></div>
                            <div class="vote-but"><a onclick="voteButFun(4)"></a></div>
                        </div>
                        <div class="people-cont mr">
                            <i class="people-left-top"></i>
                            <i class="people-right-top"></i>
                            <i class="people-right-bottom"></i>
                            <i class="people-left-bottom"></i>
                            <cote class="my-icon"></cote>
                            <cite><img src="images/people-5.jpg"/>
                                <div class="people-intro">主持人，演员，声优，综艺通告艺人，《英雄联盟》官方Coser，游戏解说 。<br/><br/>主要作品：《万万没想到》<br/>《我叫MT》等</div></cite>
                            <div class="people-name"><span>桃宝：</span><em id="5"><?=$data[4]?></em></div>
                            <div class="vote-but"><a onclick="voteButFun(5)"></a></div>
                        </div>
                        <div class="people-cont mr">
                            <i class="people-left-top"></i>
                            <i class="people-right-top"></i>
                            <i class="people-right-bottom"></i>
                            <i class="people-left-bottom"></i>
                            <cote class="mv-icon"></cote>
                            <cite><img src="images/people-6.jpg"/>
                                <div class="people-intro">清新美女、最爱萌系游戏</div></cite>
                            <div class="people-name"><span>悠悠：</span><em id="6"><?=$data[5]?></em></div>
                            <div class="vote-but"><a onclick="voteButFun(6)"></a></div>
                        </div>
                        <div class="people-cont mr">
                            <i class="people-left-top"></i>
                            <i class="people-right-top"></i>
                            <i class="people-right-bottom"></i>
                            <i class="people-left-bottom"></i>
                            <cote class="mv-icon"></cote>
                            <cite><img src="images/people-7.jpg"/>
                                <div class="people-intro">气质美女、卡牌爱好者</div></cite>
                            <div class="people-name"><span>郭洁：</span><em id="7"><?=$data[6]?></em></div>
                            <div class="vote-but"><a onclick="voteButFun(7)"></a></div>
                        </div>
                        <div class="people-cont">
                            <i class="people-left-top"></i>
                            <i class="people-right-top"></i>
                            <i class="people-right-bottom"></i>
                            <i class="people-left-bottom"></i>
                            <cote class="mv-icon"></cote>
                            <cite><img src="images/people-8.jpg"/>
                                <div class="people-intro">清纯的妹纸，钟爱卡牌游戏</div></cite>
                            <div class="people-name"><span>张沫：</span><em id="8"><?=$data[7]?></em></div>
                            <div class="vote-but"><a onclick="voteButFun(8)"></a></div>
                        </div>
                        <div class="people-cont mr">
                            <i class="people-left-top"></i>
                            <i class="people-right-top"></i>
                            <i class="people-right-bottom"></i>
                            <i class="people-left-bottom"></i>
                            <cote class="ns-icon"></cote>
                            <cite><img src="images/people-9.jpg"/>
                                <div class="people-intro">斗鱼超人气主播，获世界旅游小姐西南赛区和第五届中国•西部旅游形象小姐大赛等多项冠军</div></cite>
                            <div class="people-name"><span>张琪格：</span><em id="9"><?=$data[8]?></em></div>
                            <div class="vote-but"><a onclick="voteButFun(9)"></a></div>
                        </div>
                        <div class="people-cont mr">
                            <i class="people-left-top"></i>
                            <i class="people-right-top"></i>
                            <i class="people-right-bottom"></i>
                            <i class="people-left-bottom"></i>
                            <cote class="zn-icon"></cote>
                            <cite><img src="images/people-10.jpg"/>
                                <div class="people-intro">人气showgirl，代言过《征途》，有宅男女神之称。</div></cite>
                            <div class="people-name"><span>张优：</span><em id="10"> <?=$data[9]?></em></div>
                            <div class="vote-but"><a onclick="voteButFun(10)"></a></div>
                        </div>
                        <div class="people-cont mr">
                            <i class="people-left-top"></i>
                            <i class="people-right-top"></i>
                            <i class="people-right-bottom"></i>
                            <i class="people-left-bottom"></i>
                            <cote class="zmcm-icon"></cote>
                            <cite><img src="images/people-11.jpg"/>
                                <div class="people-intro">人气博主，星座达人，各大车展人气车模</div></cite>
                            <div class="people-name"><span>赵君裳：</span><em id="11"><?=$data[10]?></em></div>
                            <div class="vote-but"><a onclick="voteButFun(11)"></a></div>
                        </div>
                        <div class="people-cont">
                            <i class="people-left-top"></i>
                            <i class="people-right-top"></i>
                            <i class="people-right-bottom"></i>
                            <i class="people-left-bottom"></i>
                            <cote class="mvsg-icon"></cote>
                            <cite><img src="images/people-12.jpg"/>
                                <div class="people-intro">游戏主持人，ChinaJoy人气showgirl</div></cite>
                            <div class="people-name"><span>赵亚楠：</span><em id="12"><?=$data[11]?></em></div>
                            <div class="vote-but"><a onclick="voteButFun(12)"></a></div>
                        </div>
                    </div>
                    <h4>参与投票均有抽奖机会，所有奖品将在活动结束后发放</h4>
                    <br class="clear"/>
                </div>
            </div>
        </div>
        <!---->
        <div class="main-clumn">
            <div class="prize">
                <h2></h2>
                <div class="prize-cont">
                    <cite class="mr"><img src="images/jd-img.png" alt="200京东礼品卡"/></cite>
                    <cite class="mr"><img src="images/yd-img.png" alt="50元移动充值卡"/></cite>
                    <cite><img src="images/sgll-img.png" alt="三国来了限量手机壳"/></cite>
                </div>
                <div class="prize-icon"><img src="images/wy-img.png"/></div>
            </div>
        </div>
        <!---->
        <div class="main-clumn">
            <div class="vote">
                <h2><img src="images/sanguo-title.png"/></h2>
                <div class="vote-cont">
                    <h3>多重活动等你参加</h3>
                    <div class="vote-cont-list"><li>自今日起至7月15日，《三国来了》迎来老玩家回归季，在此期间将举办多重活动迎接各位主公的回归，诚意满满只为玩家。</li></div>
                    <div class="active">
                        <cite><img src="images/active-5.jpg" alt="活动一" title="活动一"/></cite>
                        <div class="active-cont">
                            <span class="act-title">活动一</span>
                            <span class="act-time">活动时间：6月23日起</span>
                            <li>同一起点，只要回归游戏，免费直升999级满级，“天下第一”指日可待！更有周周免费卡牌十连抽，你的阵容你做主！同时绝版武器，月卡激情大放送，快来《三国来了》领取吧！<a href="http://sanguo.redatoms.com/index_pc.php">[更多详情]</a></li>
                        </div>
                    </div>
                    <div class="active">
                        <cite><img src="images/active-1.jpg" alt="活动二" title="活动二"/></cite>
                        <div class="active-cont">
                            <span class="act-title">活动二</span>
                            <span class="act-time">活动时间：6月23日~7月8日 </span>
                            <li>邀请知名电竞解说、游戏女神组成美女战队，玩家投票选举“天下第一导师”，打造史上首个卡牌电竞美女战队，7月3日直播电竞比赛<a href="http://sanguo.redatoms.com/txdy/">[更多详情]</a></li>
                        </div>
                    </div>
                    <div class="active">
                        <cite><img src="images/active-2.jpg" alt="活动三" title="活动三"/></cite>
                        <div class="active-cont">
                            <span class="act-title">活动三</span>
                            <span class="act-time">活动时间：6月23日~7月9日 </span>
                            <li>活动期间内首次登陆游戏即可参与大回馈抽奖，零门槛全民赢福利！期间进入活动中心完成新资料片任务就可以获得积分抽奖，多种游戏周边、话费、Apple Watch，iPhone6 Plus等你拿！<a href="http://lt.sg.redatoms.com/forum.php?mod=viewthread&tid=233139&extra=page%3D1">[更多详情]</a></li>
                        </div>
                    </div>
                    <div class="active">
                        <cite><img src="images/active-3.jpg" alt="活动四" title="活动四"/></cite>
                        <div class="active-cont">
                            <span class="act-title">活动四</span>
                            <span class="act-time">活动时间：7月2日~7月15日 </span>
                            <li>号召好友一同收集英雄卡牌，动动手指就有机会，多种专享礼包、实物好礼，详情请关注三国来了微信公众账号：sanguoll<a>[更多详情]</a></li>
                        </div>
                    </div>
                    <div class="active mb">
                        <cite><img src="images/active-4.jpg" alt="活动五" title="活动五"/></cite>
                        <div class="active-cont">
                            <span class="act-title">活动五</span>
                            <span class="act-time">活动时间：7月9日~7月15日 </span>
                            <li>势力争霸集结号角即将再次吹响，更需要各位老玩家的强力支持，为了荣誉，死战到底！<a>[更多详情]</a></li>
                        </div>
                    </div>
                    <br class="clear"/>
                </div>
            </div>
        </div>
        <!---->
        <br class="clear"/>
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
    </div>
    <br class="clear"/>
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
<script>
    $(function(){

        $('#show').click(function(){
            $('#wind').hide();
        });
        $('.video').click(function(){
            $('#video-wind').show();
        });
        $('#video-show').click(function(){
            $('#video-wind').hide();
        });
        $('.wind').find('.close').on('click',function(){
            $(this).parents('.wind').hide();
        });

        $('close').on('click',function(){
            $(this).parents('#wind').hide();
        });


        $('.sub').click(function(){

            var regphone = /^0?1[3|4|5|8][0-9]\d{8}$/;
            var regqq = /^[0-9]*$/;

            var username = $('#username').val();
            var phone = $('#phone').val();
            var qq = $('#qq').val();
            var address = $('#address').val();

            if(username && phone && qq && address){
                //验证数据正确性
                if(checkContent(1) && checkContent(2) && checkContent(3) && checkContent(4)){
                    var flag = "joymevote";
                    jQuery.ajax({
                        "url": "addUserInfo.php",
                        "type": "post",
                        "data": {"username":username,"phone":phone,"qq":qq,"address":address,"flag":flag},
                        "success": function(msg) {
                            var data = eval('(' + msg + ')');
                            if(data['rs']==0){
                                $('#wind-successful').show();
                            }else{
                                location.reload();
                            }
                        }
                    })
                }
            }else{
                tip('姓名不能为空','手机不能为空','QQ不能为空','地址不能为空');
                return false;
            }
        });
    });

    function voteButFun(num){
        if(num){
            var flag = "joymevote";
            jQuery.ajax({
                "url": "recordVote.php",
                "type": "post",
                "data": {"vote_num":num,"flag":flag},
                "success": function(msg) {
                    var data = eval('(' + msg + ')');
                    if(data['rs'] == 0){
                        var num2 = $('#'+num).text();
                        $('#'+num).text(parseInt(num2)+1)
                        tip('请输入姓名','请输入手机号','请输入QQ号','请输入地址');
                        $('#wind').show();
                    }else if(data['rs'] == 1){
                        $("#tip_xianzhi").text('投票失败');
                        $('.wind').show();
                    }else if(data['rs'] == 2){
                        $("#tip_xianzhi").text('今天投票已达上限，请明天再试');
                        $('.wind').show();
                    }else if(data['rs'] == 3){
                        $("#tip_xianzhi").text('非法投票');
                        $('.wind').show();
                    }else{
                        $("#tip_xianzhi").text('程序异常');
                        $('.wind').show();
                    }
                }
            })
        }
    }

    function checkContent(num){

        var username = $('#username').val();
        var phone = $('#phone').val();
        var qq = $('#qq').val();
        var address = $('#address').val();

        var regphone = /^0?1[3|4|5|8][0-9]\d{8}$/;
        var regqq = /^[0-9]*$/;

        if(num ==1){
            if(username == ''){
                $("#tip_name").text('姓名不能为空');
                return false;
            }else{
                $("#tip_name").text('');
                return true;
            }
        }else if(num ==2){
            if(phone == ''){
                $("#tip_phone").text('手机不能为空');
                return false;
            }else{
                if (!regphone.test(phone)) {
                    $("#tip_phone").text('手机格式错误');
                    return false;
                }else{
                    $("#tip_phone").text('');
                    return true;
                }
            }
        }else if(num ==3){
            if(qq == ''){
                $("#tip_qq").text('QQ不能为空');
                return false;
            }else{
                if (!regqq.test(qq)) {
                    $("#tip_qq").text('QQ格式错误');
                    return false;
                }else{
                    $("#tip_qq").text('');
                    return true;
                }
            }
        }else{
            if(address == ''){
                $("#tip_address").text('地址不能为空');
                return false;
            }else{
                $("#tip_address").text('');
                return true;
            }
        }
    }

    function tip(tip1,tip2,tip3,tip4){

        $("#tip_name").text(tip1);
        $("#tip_phone").text(tip2);
        $("#tip_qq").text(tip3);
        $("#tip_address").text(tip4);
    }

    $('#show2').click(function(){

        $('#username').val('');
        $('#phone').val('');
        $('#qq').val('');
        $('#address').val('');
        $("#wind-successful").hide();
        $('#wind').hide();
    })
</script>
</body>
</html>