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
				$('.sxyx-dialog').hide();
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