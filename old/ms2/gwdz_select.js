function wikiselect(){
	var self = this;
	self.init();
}

wikiselect.prototype = {
	"selectedRarity"	: null,
	"selectedFight"	: null,
	"selectedAttribute"	: null,
	"selectedSpecies"	: null,
	"selectedHit"	: "",
	
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
		//属性
		$("#attribute span").click(function(){
            $('#attribute span').removeClass('selected');

            var attribute = $(this).attr('data');
            if(link.selectedAttribute == attribute) link.selectedAttribute=null;
            else{
                link.selectedAttribute=attribute;
                $(this).addClass('selected');
            }
            // link.drawMainBox();
		});
		
		//稀有度
		$("#rarity p span").click(function(){
            $('#rarity p span').removeClass('selected');

            var rarity = $(this).attr('data');
            if(link.selectedRarity == rarity) link.selectedRarity=null;
            else{
                link.selectedRarity=rarity;
                $(this).addClass('selected');
            }
            // link.drawMainBox();
		});
		
		//成长偏向
		$("#fight p span").click(function(){
            $('#fight p span').removeClass('selected');

            var fight = $(this).attr('data');
            if(link.selectedFight == fight) link.selectedFight=null;
            else{
                link.selectedFight=fight;
                $(this).addClass('selected');
            }
            // link.drawMainBox();
		});
		
		//攻击类型
		$("#hit p span b").click(function(){
            $('#hit p span b').removeClass('selected');

            var hit = $(this).html();
            if(link.selectedHit == hit) link.selectedHit=null;
            else{
                link.selectedHit=hit;
                $(this).addClass('selected');
            }
            // link.drawMainBox();
		});
		
		//种族
		$("#species div").click(function(){
            $("#species div").removeClass('selected');

            var species = $(this).attr('data');
            if(link.selectedSpecies == species || species==0) link.selectedSpecies=null;
            else{
                link.selectedSpecies=species;
                $(this).addClass('selected');
            }
            // link.drawMainBox();
		});
		
		//筛选
		$(".sel_submit").click(function(){
			link.page = 1;
			link.totalpage = 1;
			link.drawMainBox('replace');
		});
		
		//取消
		$(".sel_close").click(function(){
			link.page = 1;
			link.totalpage = 1;
			link.reset();
		});
	},
	
	"drawMainBox" : function(op){
		var link = this;
		
		var rarity = link.selectedRarity;
		var fight = link.selectedFight;
		var attribute = link.selectedAttribute;
		var species = link.selectedSpecies;
		var hit = link.selectedHit;
		
		var list = [];

		var dataList = window.data;
		var list1 = [];
		if(null != rarity){
			for(i in dataList){
				var item = dataList[i];
				if(item.rarity == rarity){
					list1[list1.length]=item;
				}
			}
		}else{
			list1 = window.data;
		}

		var dataList2 = list1;
		var list2 = [];
		if(null != fight){
			for(i in dataList2){
				var item = dataList2[i];
				if(item.fight == fight){
					list2[list2.length]=item;
				}
			}
		}else{
			list2 = list1;
		}
		
		var dataList3 = list2;
		var list3 = [];
		if(null != attribute){
			for(i in dataList3){
				var item = dataList3[i];
				if(item.attribute == attribute){
					list3[list3.length]=item;
				}
			}
		}else{
			list3= list2;
		}
		
		var dataList4 = list3;
		var list4 = [];
		if(null != species){
			for(i in dataList4){
				var item = dataList4[i];
				if(item.species == species){
					list4[list4.length]=item;
				}
			}
		}else{
			list4 = list3;
		}
		
		var dataList5 = list4;
		if("" != hit){
			for(i in dataList5){
				var item = dataList5[i];
				if(item.hit == hit){
					list[list.length]=item;
				}
			}
		}else{
			list = list4;
		}
		
		if(null == rarity && null == fight && null == attribute && null == species && "" == hit){
			list = [];
			for(i in dataList){
				var item = dataList[i];
				if($.inArray(item.no, window.defaultid) != -1){
					list[list.length]=item;
				}
			}
		}

		var listHtml ='';
		var offset = 12;
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
			listHtml += '<div><div class="pet_list_box"><div><em><a href=""></a><a href="http://ms.joyme.com/wxwiki/'+item.articleid+'.shtml" title="'+item.name+'"><img alt="'+item.pic+'" src="./icon/'+item.pic+'" style="width: 100%;max-width: 75px;height:auto" /></a><span class="floatStar fs_4"><cite class="xing_num">'+item.rarity+'</cite><cite class="xing_Img">★</cite></span></em><code>No.'+item.no+'</code><b>'+item.name+'</b></div></div></div>';
		}
		if(op=='replace'){
			$("#cards").html(listHtml);
		}else{
			$("#cards").append(listHtml);
		}
	},
	
	"reset" : function(){
		var link = this;
		link.selectedRarity = null;
		link.selectedFight = null;
		link.selectedAttribute = null;
		link.selectedSpecies = null;
		link.selectedHit = "";
		link.page = 1;
		link.totalpage = 1;
		
		link.drawMainBox('replace');
	}
}

$(function(){
	var selected = new wikiselect();
});