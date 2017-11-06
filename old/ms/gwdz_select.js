function wikiselect(){
	var self = this;
	self.init();
}

wikiselect.prototype = {
	"selectedRarity"	: null,
	"selectedFight"	: null,
	"selectedAttribute"	: null,
	"selectedSpecies"	: null,
	"selectedHit"	: null,
	
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
            if(link.selectedAttribute == attribute || link.storeageGet('attribute') == attribute){
				link.storeageRemove('attribute');
				link.selectedAttribute=null;
			}else{
                link.selectedAttribute=attribute;
                $(this).addClass('selected');
				link.storeageSet('attribute', link.selectedAttribute);
            }
            // link.drawMainBox();
		});
		
		//稀有度
		$("#rarity p span").click(function(){
            $('#rarity p span').removeClass('selected');

            var rarity = $(this).attr('data');
            if(link.selectedRarity == rarity || link.storeageGet('attribute') == attribute){
				link.storeageRemove('rarity');
				link.selectedRarity=null;
			}else{
                link.selectedRarity=rarity;
                $(this).addClass('selected');
				link.storeageSet('rarity', link.selectedRarity);
            }
            // link.drawMainBox();
		});
		
		//成长偏向
		$("#fight p span").click(function(){
            $('#fight p span').removeClass('selected');

            var fight = $(this).attr('data');
            if(link.selectedFight == fight || link.storeageGet('attribute') == attribute){
				link.storeageRemove('fight');
				link.selectedFight=null;
			}else{
                link.selectedFight=fight;
                $(this).addClass('selected');
				link.storeageSet('fight', link.selectedFight);
            }
            // link.drawMainBox();
		});
		
		//攻击类型
		$("#hit p span b").click(function(){
            $('#hit p span b').removeClass('selected');

            var hit = $(this).html();
            if(link.selectedHit == hit || link.storeageGet('attribute') == attribute){
				link.storeageRemove('hit');
				link.selectedHit=null;
			}else{
                link.selectedHit=hit;
                $(this).addClass('selected');
				link.storeageSet('hit', link.selectedHit);
            }
            // link.drawMainBox();
		});
		
		//种族
		$("#species div").click(function(){
            $("#species div").removeClass('selected');

            var species = $(this).attr('data');
            if(link.selectedSpecies == species || species==0 || link.storeageGet('attribute') == attribute){
				link.storeageRemove('species');
				link.selectedSpecies=null;
			}else{
                link.selectedSpecies=species;
                $(this).addClass('selected');
				link.storeageSet('species', link.selectedSpecies);
            }
            // link.drawMainBox();
		});
		
		//筛选
		$(".sel_submit").click(function(){
			$("#gropPet").hide();
			$("#gropPet").parent('div').removeClass('pet_arr');
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
		// link.storeageGet('attr');
		var rarity = link.selectedRarity || this.storeageGet('rarity');
		var fight = link.selectedFight || this.storeageGet('fight');
		var attribute = link.selectedAttribute || this.storeageGet('attribute');
		var species = link.selectedSpecies || this.storeageGet('species');
		var hit = link.selectedHit || this.storeageGet('hit');
		
		// console.log('rarity:', rarity);
		// console.log('fight:', fight);
		// console.log('attribute:', attribute);
		// console.log('species:', species);
		// console.log('hit:', hit);
		// var attr = {rarity:rarity, fight:fight, attribute:attribute, species:species, hit:hit};
		// link.storeageSet(JSON.stringify(attr));
		var group = link.selectedGroup;
		
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
			$("#rarity span[data='"+rarity+"'] b").addClass('on');
		}else{
			list1 = window.data;
			$("#rarity span[data='"+rarity+"'] b").removeClass('on');
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
			$("#fight span[data='"+fight+"'] b").addClass('on');
		}else{
			list2 = list1;
			$("#fight span[data='"+fight+"'] b").removeClass('on');
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
			$("#attribute span[data='"+attribute+"'] b").addClass('on');
		}else{
			list3= list2;
			$("#attribute span[data='"+attribute+"'] b").removeClass('on');
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
			$("#species span[data='"+species+"'] b").addClass('on');
		}else{
			list4 = list3;
			$("#species span[data='"+species+"'] b").removeClass('on');
		}
		
		var dataList5 = list4;
		if(null != hit){
			for(i in dataList5){
				var item = dataList5[i];
				if(item.hit == hit){
					list[list.length]=item;
				}
			}
			$("#hit span[data='"+hit+"'] b").addClass('on');
		}else{
			list = list4;
			$("#hit span[data='"+hit+"'] b").removeClass('on');
		}

		if(null == rarity && null == fight && null == attribute && null == species && null == hit){
			list = [];
			$("#gropPet").show();
			$("#gropPet").parent('div').addClass('pet_arr');
		}else{
			$("#gropPet").hide();
			$("#gropPet").parent('div').removeClass('pet_arr');
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

		if(list != ''){
			for(var i=start; i<end; i++){
				var item = list[i];
				listHtml += '<div><div class="pet_list_box"><div><em><a href=""></a><a href="http://ms.joyme.com/wxwiki/'+item.articleid+'.shtml" title="'+item.name+'"><img alt="'+item.pic+'" src="./icon/'+item.pic+'" style="width: 100%;max-width: 75px;height:auto" /></a><span class="floatStar fs_'+item.attribute+'"><cite class="xing_num">'+item.rarity+'</cite><cite class="xing_Img">★</cite></span></em><code>No.'+item.no+'</code><b>'+item.name+'</b></div></div></div>';
			}
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
		// removeItem();
		link.storeageClear();
		$("#gropPet").show();
		$("#gropPet").parent('div').addClass('pet_arr');
		// link.drawMainBox('replace');
	},
	
	"storeageSet" : function(attr, value){
		var link = this;
		if (link.supports_html5_storage()) {
			window.localStorage.setItem(attr, value);
		}
	},
	
	"storeageGet" : function(attr){
		var link = this;
		if (link.supports_html5_storage()) {
			return window.localStorage.getItem(attr);
		}else{
			return null;
		}
	},
	
	"storeageClear" : function(){
		var link = this;
		if (link.supports_html5_storage()) {
			window.localStorage.clear();
		}
	},
	
	"storeageRemove" : function(attr){
		var link = this;
		if (link.supports_html5_storage()) {
			var s = window.localStorage.removeItem(attr);
		}
	},
	
	"supports_html5_storage" : function(){
		try {
			return 'localStorage' in window && window['localStorage'] !== null;
		} catch (e) {
			return false;
		}
	}
}

$(function(){
	var selected = new wikiselect();
});