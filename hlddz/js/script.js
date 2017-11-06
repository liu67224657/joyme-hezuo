function checkTopLoop(){
	
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
    if ($('#hotNews-loop .swiper-slide').length > 2){
        var mySwiper2 = new Swiper('#hotNews-loop',{
            loop: false,
            paginationClickable: false,
            mode: 'horizontal',  //水平
            cssWidthAndHeight: true
        });
        /* 图片展示翻页 */
        document.getElementById('l-btn').addEventListener('touchstart', function(ev){
            ev.stopPropagation();
            ev.preventDefault();
            mySwiper2.swipePrev();
        }, false);

        /* 图片展示翻页 */
        document.getElementById('r-btn').addEventListener('touchstart', function(ev){
            ev.stopPropagation();
            ev.preventDefault();
            mySwiper2.swipeNext();
        }, false);
    }
}
checkTopLoop();

var model = {
    int:function(){
        model.tabView({
            container : $(".tab-box"),
            tabTag : $(".tab-menu"),
            tabCon : $(".tab-main")
        });
    },
    tabView:function(opt){
        var container = opt.container;
        var tabTag = opt.tabTag;
        var oneTag =tabTag.children();
        var tabCon = opt.tabCon;
        var oneCon = tabCon.children();
        oneTag.on("touchstart",function(){
            var index = $(this).index();
            $(this).addClass("active").siblings().removeClass("active");
            $(oneCon).eq(index).addClass("active").siblings().removeClass("active");
        })
    }
};
 model.int();
 var tools={
         int:function(){
            tools.checkQuickNav();
            tools.getTop();
            tools.changeView();
            },
         checkQuickNav:function(){
            $('.menu-icon').on('touchstart',function(e){
                e.preventDefault();
                $('.opacity-bg').show();
                var timer=null;
                timer=setTimeout(function(){
                    clearTimeout(timer);
                    $('.menu-box').addClass('ind-active');
                },0);
                $(this).hide();
            });
            $('.btn-close').on('touchstart',function(){
                $('.opacity-bg').hide();
                $('.menu-box').removeClass('ind-active');
                $('.menu-icon').show();
            });
             $('.menu-con,.opacity-bg').on('touchmove',function(e){
                e.preventDefault();
             });
              $('.btn-close,opacity-bg').on('touchstart',function(e){
                e.preventDefault();
             });
              $('.btn-close').on('touchstart',function(e){
                e.stopPropagation();
              });
            },
         getTop:function(){
             var $runTop=$('.run-top');
             var $wrapper=$('.wrapper');
             var $menuIcon = $(".menu-icon");
             $wrapper.on('touchstart',function(e){
                 e.stopPropagation();
                });
             $wrapper.scroll(function(){
                 var sTop=$wrapper.scrollTop();
                 // console.log(sTop);
                 if(sTop>50){
                     $runTop.show();
                     $menuIcon.css({"bottom":"120px"});
                     }else{
                     $runTop.hide();
                     $menuIcon.css({"bottom":"80px"});   
                    }
                });
             $runTop.on('touchstart',function(e){
                 e.stopPropagation();
                 $wrapper.animate({scrollTop:0},500);
                })
            },
            'changeView':function(){
                var h = $(window).height();
                if(h<=480){
                    $(".info-menu").find("a").css({"padding":"5px 0"});
                    $(".info-menu a span").find("b").css({"width":"48px","height":"48px","margin":"0 auto"});
                }
            }
        };
    tools.int();