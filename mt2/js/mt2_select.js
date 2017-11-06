function wikiselect(){
	var self = this;
	self.init();
}

wikiselect.prototype = {
	"selectedStyleClass"	: null,
	"selectedOccupation"	: null,
	
	"page" : 1,
	"totalpage" : 1,
	
	"init"	: function(){
		this.eventBind();
		this.drawMainBox('replace');
	},
	
	"eventBind":function(){
		this.bindFilterEvent();
	},
	
	"bindFilterEvent":function(){
        var link = this;
		//类型
		$("#sx_classes cite").click(function(){
            $('#sx_classes cite').removeClass('active');

            var styleClass = $(this).attr('data');
            if(link.selectedStyleClass == styleClass) link.selectedStyleClass=null;
            else{
                link.selectedStyleClass=styleClass;
                $(this).addClass('active');
            }
            // link.drawMainBox();
		});
		
		//职业
		$("#sx_character cite").click(function(){
            $('#sx_character cite').removeClass('active');

            var occupation = $(this).attr('data');
            if(link.selectedOccupation == occupation) link.selectedOccupation=null;
            else{
                link.selectedOccupation=occupation;
                $(this).addClass('active');
            }
            // link.drawMainBox();
		});
		// 筛选按钮
		$('.yx-list-title').click(function(){
			$('#op-bg').show();
			$('.sxyx-dialog').show();
		});
		
		//筛选
		$("#sx_sure").click(function(){
			$('#op-bg').hide();
			$('.sxyx-dialog').hide();
			link.page = 1;
			link.totalpage = 1;
			link.drawMainBox('replace');
		});
		
		//取消
		$("#sx_cancel").click(function(){
			$('#op-bg').hide();
			$('.sxyx-dialog').hide();
			link.page = 1;
			link.totalpage = 1;
			link.reset();
		});
	},
	
	"drawMainBox" : function(op){
		var link = this;
		
		var styleclass = link.selectedStyleClass;
		var occupation = link.selectedOccupation;
		console.log('styleclass:',styleclass);
		console.log('occupation:',occupation);
		// return false;
		var list = [];

		var dataList = window.data;
		var list1 = [];
		if(null != styleclass){
			for(i in dataList){
				var item = dataList[i];
				if(item.styleClass == styleclass){console.log('occupation:',styleclass);
					list1[list1.length]=item;
				}
			}
		}else{
			list1 = window.data;
		}

		var dataList2 = list1;
		if(null != occupation){
			for(i in dataList2){
				var item = dataList2[i];
				if(item.occupation == occupation){
					list[list.length]=item;
				}
			}
		}else{
			list = list1;
		}
		
		if(null == styleclass && null == occupation){
			list = [];
			for(i in dataList){
				var item = dataList[i];
				// if($.inArray(item.no, window.defaultid) != -1){
					list[list.length]=item;
				// }
			}
		}

		var listHtml ='';
		var offset = 1000;
		var start = (link.page-1)*offset;
		var end = start+offset;
		if(list.length < end){
			end = list.length;
			$("#ismore").html('');
		}
		
		link.totalpage = Math.ceil(list.length/offset);
		if(link.totalpage>1 && end != list.length-1){
			var buttonstr = '<div class="load_btn">点击加载</div>';
			var newObject = $(buttonstr);
			newObject.click(function(){
				link.page++;
				link.drawMainBox('append');
			});
			$("#ismore").html(newObject);
		}
		
		for(var i=start; i<end; i++){
			var item = list[i];
			// listHtml += '<div><div class="pet_list_box"><div><em><a href=""></a><a href="http://www.joyme.com/wxwiki/ms/'+item.articleid+'.shtml" title="'+item.name+'"><img alt="'+item.pic+'" src="./icon/'+item.pic+'" style="width: 100%;max-width: 75px;height:auto" /></a><span class="floatStar fs_'+item.attribute+'"><cite class="xing_num">'+item.rarity+'</cite><cite class="xing_Img">★</cite></span></em><code>No.'+item.no+'</code><b>'+item.name+'</b></div></div></div>';
			listHtml += '<a href="'+item.url+'"><span class="cp-headImg"><img src="'+item.pic+'" alt="'+item.name+'" title="'+item.name+'"></span><b>'+item.name+'</b></a>';
		}
		if(op=='replace'){
			$("#hero_list").html(listHtml);
		}else{
			$("#hero_list").append(listHtml);
		}
	},
	
	"reset" : function(){
		var link = this;
		link.selectedStyleClass = null;
		link.selectedOccupation = null;
		link.page = 1;
		link.totalpage = 1;
		
		link.drawMainBox('replace');
	}
}

$(function(){
	var selected = new wikiselect();
});