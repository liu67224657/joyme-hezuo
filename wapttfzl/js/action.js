var action={
	'toolsInfo':function(){
		action.checkTopLoop();
		action.checkTab();
		action.checkQuickNav();
		action.zan();
		action.checkTab();
		action.IosToAndroid();
		action.footerPos();
	},
	/* 头图轮播 */
	'checkTopLoop':function(){
		if ($('#pic-loop .swiper-slide').length > 2){
			var mySwiper = new Swiper('#pic-loop',{
				loop: true,
				pagination: '.pagination',
				paginationClickable: false,
				mode: 'horizontal',  //水平
				cssWidthAndHeight: true
			});
			setInterval(function(){mySwiper.swipeNext()}, 3000);
		}
	},
	//选项卡
	'checkTab':function(){
		$('.menu-Tab span,.tab-menu span').each(function(idx){
			$(this).on('touchstart', function(ev){
				ev.stopPropagation();
				ev.preventDefault();
				changeTab(idx);
			});
		})
		function changeTab(idx){
			$('.menu-Tab span,.tab-menu span').removeClass('active').eq(idx).addClass('active');
			$($('.zb-tabList,.tab-main')[idx]).show().siblings('.zb-tabList,.tab-main').hide();
		};
		$('.gq-tag cite').each(function(idx) {
            $(this).on('touchstart',function(){
				$(this).addClass('active').parent('span').siblings('span').children().removeClass('active');
				$($('.gq-pic')[idx]).show().siblings('.gq-pic').hide();	
			})
        });
	},
	'checkQuickNav':function (){
			var startbtn=$('#quick-nav-btn');
			var op= $('#op-bg');
			var t = $('#quick-nav');
			var closebtn=$('#ind-btn-close');
			//阻止冒泡,防止屏幕后面的内容滚动
			function evStop(ev){
				ev.stopPropagation();
				ev.preventDefault();
			}
			t.on('touchmove',evStop);
			op.on('touchmove',evStop);
			op.on('touchstart',function(ev){
				ev.stopPropagation();
				ev.preventDefault();
				startbtn.removeClass('close').text('菜单');
				t.removeClass('close');
				$(this).hide();
			});
			startbtn.on('touchstart', function(ev){
				ev.stopPropagation();
				ev.preventDefault();
				if($(this).hasClass('close')){
					$(this).removeClass('close').text('菜单');
					t.removeClass('close');
					op.hide();
				}else{
					$(this).addClass('close').text('关闭');
					t.addClass('close');
					op.show();	
				}
			});
		},
	//点赞字数增加
	'zan':function(){
		$('.zan').click(function(){
			var zanNum = $('.zan span code').text();
			zanNum=parseInt(zanNum)+1;
			$(this).find('code').text(zanNum);
		});
	},
	//判断IOS,安卓设备
	'IosToAndroid':function(){
		if (/android/i.test(navigator.userAgent)){
				$('#phone-mold').text('Android 下载');
				$('.ad-btn').width(108)
				$('.ad-btn b').css({'font-size':'12px'});
		}if (/ipad|iphone|mac/i.test(navigator.userAgent)){
				$('#phone-mold').text('IOS 下载');
		}
	},
	//版权始终在底部
	'footerPos':function(){
		var wH=$(window).height();
		var bodyH=$(document.body).height();
		var footer=$('#footer'); 
		if(bodyH<wH){
			footer.addClass('position');
			}else{
			footer.removeClass('position');	
		}
	},
}
action.toolsInfo();	

var tools={
     int:function(){
        tools.checkQuickNav();
        tools.getTop();
        },
     checkQuickNav:function(){
        $('#quick-nav-btn').on('touchstart',function(e){
            e.stopPropagation();
            $('#opacity-bg').show();
            $('#quick-nav').addClass('ind-active');
            $(this).hide();
        });
        $('.ind-btn-close').on('touchstart',function(){
            $('#opacity-bg').hide();
            $('#quick-nav').removeClass('ind-active');
            $('#quick-nav-btn').show();
        });
         $('#quick-nav').on('touchstart',function(e){
            e.preventDefault();
         });
        },
     getTop:function(){
         var $getTop=$('#getTop');
         var $wrapper=$('#wrapper');
         var $quicknav = $("#quick-nav-btn");
         var $getTop=$("#getTop");
         $wrapper.on('touchstart',function(e){
             e.stopPropagation();
            });
         $wrapper.scroll(function(){
             var sTop=$wrapper.scrollTop();
             console.log(sTop);
             if(sTop>50){
                 $getTop.show();
                 $quicknav.css({"bottom":"120px"});
                 $getTop.css({"bottom":"80px"});
                 }else{
                 $getTop.hide();
                 $quicknav.css({"bottom":"80px"});
                 $getTop.css({"bottom":"40px"});   
                }
            });
         $getTop.on('touchstart',function(e){
             e.stopPropagation();
             $wrapper.animate({scrollTop:0},500);
            })
        }
    }
tools.int();

/* tab切换1 */
function checkTab(){
	$('.pt-tabMenu span,.gq-tabMenu span').each(function(idx){
		$(this)[0].addEventListener('touchstart', function(ev){
			ev.stopPropagation();
			ev.preventDefault();
			changeTab(idx);
		}, false)
	})
	/**/
	function changeTab(idx){
		$('.menu-Tab span,.tab-menu span').removeClass('active').eq(idx).addClass('active');
		$($('.zb-tabList,.tab-main')[idx]).show().siblings('.zb-tabList,.tab-main').hide();
	};
	$('#Checkpoint span').each(function(idx) {
        $(this).on('touchstart',function(){
			$(this).addClass('onTwo').siblings().removeClass('onTwo');
			$($('.gq-pic')[idx]).show().siblings('.gq-pic').hide();	
		})
    });
    /**/
    function changeTab(idx){
        $('.pt-tabMenu span,.gq-tabMenu span').removeClass('on').eq(idx).addClass('on');
        $($('.com-tab')[idx]).show().siblings('.com-tab').hide();
    }
    
	$('.skill-list span').each(function(idx){
		$(this)[0].addEventListener('touchstart',function(){
			$(this).parent('div').parent('.skill-list').parent('.com-tab').hide();
			$('.pt-tabMenu span:last').addClass('on').siblings('span').removeClass();
			$('.pt-tabBox >div.com-tab:last').show();
		});
	});
	$('.gk-tabMenu span').on('touchstart',function(){
		var ind=$(this).index();
		$(this).addClass('on').siblings().removeClass('on');
		$('.pt-tabBox .gk-tabMain').eq(ind).addClass('on').siblings().removeClass('on');
	})
}
checkTab();
/*        $($('.com-tab')[this]).show().siblings('.com-tab').hide();*/
function qwe(){
	$('#Checkpoint span').on("click",function(){
		$('this').addClass('onTwo').siblings().removeClass('onTwo');
	})
}
qwe();

function grade(){
var grade=true;
var plNum=$('.pf_user b').text();
$('.grade_icon cite').mouseover(function(){
  $(this).addClass('on').prevAll('cite').addClass('on');
    $(this).nextAll('cite').removeClass('on');
  if(!$('.grade_icon cite:first-child').hasClass('on')){
    $('.grade_icon cite:first-child').removeClass('on');
  }else{
    $('.grade_icon cite:first-child').addClass('on');
    }
});
$('.pf_btn').click(function(){
        plNum=parseInt(plNum)+1+'人';
        $('.pf_user b').text(plNum);
        if(grade){
           $('.pf_btn').text("修改");
           $('#opacity_bg,.pf_tips').show();
           $('#opacity_bg').css({
               'z-index':'20',
               });
           $('.pf_tips').css({
               'z-index':'21',
               })
           grade=false;
        }else{
           $('.pf_btn').text("评分");            
           grade=true;
        };
    });
    $('.pf_close').click(function(){
         $('#opacity_bg,.pf_tips').hide();
         $('#opacity_bg,.pf_tips').css({
               'z-index':'9',
            });
    });
    var varNum=0;
    $('.pic_comment span').click(function(){
        var countNum=$(this).children('code').text();
        varNum=parseInt(countNum)+1
        $(this).children('code').text(varNum);
    })
}
grade();

