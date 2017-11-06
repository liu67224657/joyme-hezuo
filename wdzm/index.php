<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>我的着迷我设计之作品评选</title>
	<link rel="stylesheet" href="css/index.css">
</head>
<?php
$forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);
if(preg_match("/mobile/",$forasp)){
    die("只能通过PC端访问！");
}
?>
<body>
	<div class="cen-bg">
		<!-- 投票规则开始 -->
		<div class="vote"></div>
		<!-- 投票规则结束 -->
		<!-- 作品展示 start -->
		<div class="show">
			<div class="show-title"></div>
			<div class="show-main">
				<ul>
					<li >
						<a target="_blank"  href="/wdzm/page.php?id=1" class="show-po">
							<div class="show-radius">
								<div class="show-num">
									<span class="show-num-1 show-num-span"></span>
								</div>
							</div>
							<img class="show-img" src="zpimages/1/xiaoyu.JPG"/>
                            <input type="hidden" value="1" name="vote">
						</a>
						<div>
							<span class="show-text"></span>
							<span class="show-border"></span>
						</div>

					</li>
					<li>
						<a target="_blank"  href="/wdzm/page.php?id=2" class="show-po">
							<div class="show-radius">
								<div class="show-num">
									<span class="show-num-2 show-num-span"></span>
								</div>
							</div>
							<img class="show-img" src="zpimages/2/bgqwiki.jpg"/>
                            <input type="hidden" value="2" name="vote">
						</a>
						<div>
							<span class="show-text"></span>
							<span class="show-border"></span>
						</div>

					</li>
					<li>
						<a target="_blank"  href="/wdzm/page.php?id=3" class="show-po">
							<div class="show-radius">
								<div class="show-num">
									<span class="show-num-3 show-num-span"></span>
								</div>
							</div>
							<img class="show-img" src="zpimages/3/UEDWHcl-1.jpg"/>
                            <input type="hidden" value="3" name="vote">
						</a>
						<div>
							<span class="show-text"></span>
							<span class="show-border"></span>
						</div>

					</li>
					<li>
						<a target="_blank"  href="/wdzm/page.php?id=4" class="show-po">
							<div class="show-radius">
								<div class="show-num">
									<span class="show-num-4 show-num-span"></span>
								</div>
							</div>
							<img class="show-img" src="zpimages/4/SPOsdw-2.jpg"/>
                            <input type="hidden" value="4" name="vote">
						</a>
						<div>
							<span class="show-text"></span>
							<span class="show-border"></span>
						</div>

					</li>
					<li>
						<a target="_blank"  href="/wdzm/page.php?id=5" class="show-po">
							<div class="show-radius">
								<div class="show-num">
									<span class="show-num-5 show-num-span"></span>
								</div>
							</div>
							<img class="show-img" src="zpimages/5/xiaoru4.JPG"/>
                            <input type="hidden" value="5" name="vote">
						</a>
						<div>
							<span class="show-text"></span>
							<span class="show-border"></span>
						</div>

					</li>
					<li>
						<a target="_blank"  href="/wdzm/page.php?id=6" class="show-po">
							<div class="show-radius">
								<div class="show-num">
									<span class="show-num-6 show-num-span"></span>
								</div>
							</div>
							<img class="show-img" src="zpimages/6/ZODzc.jpeg"/>
                            <input type="hidden" value="6" name="vote">
						</a>
						<div>
							<span class="show-text"></span>
							<span class="show-border"></span>
						</div>

					</li>
					<li>
						<a target="_blank"  href="/wdzm/page.php?id=7" class="show-po">
							<div class="show-radius">
								<div class="show-num">
									<span class="show-num-7 show-num-span"></span>
								</div>
							</div>
							<img class="show-img" src="zpimages/7/SPOsdw.jpg"/>
                            <input type="hidden" value="7" name="vote">
						</a>
						<div>
							<span class="show-text"></span>
							<span class="show-border"></span>
						</div>

					</li>
					<li>
						<a target="_blank"  href="/wdzm/page.php?id=8" class="show-po">
							<div class="show-radius">
								<div class="show-num">
									<span class="show-num-8 show-num-span"></span>
								</div>
							</div>
							<img class="show-img" src="zpimages/8/dgnzsq.JPG"/>
                            <input type="hidden" value="8" name="vote">
						</a>
						<div>
							<span class="show-text"></span>
							<span class="show-border"></span>
						</div>

					</li>
					<li>
						<a target="_blank"  href="/wdzm/page.php?id=9" class="show-po">
							<div class="show-radius">
								<div class="show-num">
									<span class="show-num-9 show-num-span"></span>
								</div>
							</div>
							<img class="show-img" src="zpimages/9/a.jpg"/>
                            <input type="hidden" value="9" name="vote">
						</a>
						<div>
							<span class="show-text"></span>
							<span class="show-border"></span>
						</div>

					</li>
					<li>
						<a target="_blank"  href="/wdzm/page.php?id=10" class="show-po">
							<div class="show-radius">
								<div class="show-num">
									<span class="show-num-10 show-num-span"></span>
								</div>
							</div>
							<img class="show-img" src="zpimages/10/1.jpg"/>
                            <input type="hidden" value="10" name="vote">
						</a>
						<div>
							<span class="show-text"></span>
							<span class="show-border"></span>
						</div>

					</li>
				</ul>
			</div>
		</div>
		<!-- 作品展示 end -->
		<!-- 提交 start -->
		<div class="cen-btn"></div>
		<!-- 提交 end -->
		<div class="cen-bottom"></div>
		<!-- 弹出框 start -->
		<div class="vote-ok">
            <h1 id="result"></h1>
            <p id="result2"></p>
		</div>
		<!-- 弹出框 start -->
		<div class="vote-no">
			<h1>最多可投2票哦！</h1>
		</div>
	</div>
	<div class="show-bg">
		<span class="show-bg-1"></span>
		<span class="show-bg-2"></span>
		<span class="show-bg-3"></span>
		<span class="show-bg-4"></span>
		<span class="show-bg-5"></span>
		<span class="show-bg-6"></span>
		<span class="show-bg-7"></span>
		<span class="show-bg-8"></span>
		<span class="show-bg-9"></span>
		<span class="show-bg-10"></span>
		<span class="show-bg-11"></span>
	</div>
	<script src="http://reswiki.joyme.com/js/jquery-1.9.1.min.js" language="javascript"></script>
	<script type="text/javascript">
		$(function(){
			$(".show-main .show-text").on('click',function(){
				if(!$(this).hasClass('on')){
					$(this).addClass('on');
					$(this).parent("div").prev("a").addClass("on");
					$(this).parent("div").prev("a").find(".show-num").addClass("on");
				}else{
					$(this).removeClass("on");
					$(this).parent("div").prev("a").removeClass("on");
					$(this).parent("div").prev("a").find(".show-num").removeClass("on");
				}
				if($('.show-text.on').length>2){
					$(this).removeClass("on");
					$(this).parent("div").prev("a").removeClass("on");
					$(this).parent("div").prev("a").find(".show-num").removeClass("on");
					$(".vote-no").fadeIn(400);
				}
			});
			$(".cen-btn").click(function(){
                var str1 = "";
                var str2 = "";
                var vote_code = "";
                var flag = "joymevote";
                //获取class内hidden值
                $("ul li a[class='show-po on'] input[name='vote']").each(function(){
                    vote_code+=$(this).val()+"-";
                })
                //如果投票数量不为空
                if(vote_code){
                    jQuery.ajax({
                        "url": "vote.php",
                        "type": "post",
                        "data": {"vote_code":vote_code,"flag":flag},
                        "success": function(msg) {
                            if(msg=="NO"){
                                str1 = "投票失败！"
                                str2 = "您已经投过票了"
                            }else if(msg=="DANGER"){
                                str1 = "投票失败！"
                                str2 = "不允许非法投票"
                            }else{
                                str1 = "投票成功！"
                                str2 = "感谢您对本次活动支持"
                            }
                            $("#result").text(str1);
                            $("#result2").text(str2);
                        }
                    })
                    $("#result").text(str1);
                    $("#result2").text(str2);
                }else{
                    str1 = "请选择要投票的作品"
                    $("#result").text(str1);
                }
                $(".vote-ok").fadeIn(400);
            });
			$(".vote-ok").click(function(){
				$(this).fadeOut(400);
			});
			$(".vote-no").click(function(){
				$(this).fadeOut(400);
			});
			/*$('.show-main li a').on('click',function(e){
				 if (e && e.stopPropagation){
                    e.stopPropagation()
                 }else{
                   window.event.cancelBubble=true
				 }
			})*/
		})
	</script>
</body>
</html>