var searchTools={ 
	'int':function(){
		searchTools.touch();
		searchTools.textbar();
		searchTools.txtList();
		},
	'touch':function(){
		$("#textfield").bind('keyup blur',function(){
			var w = $("#textfield").val();
			if(w==""){
				$(".searchBox").html("");
			}else{
				$("#button").on('click',function(){
					var result = searchTools.search_txt(w);
					searchTools.set_txt(result);
				});
			}}).focus(function(){
				//
				if($(this).val() == $(this).attr('title')){
					$(this).css('color','#666').val('');
					}
			}).blur(function(){
		if($(this).val() == ""){
				$(this).css('color','#666').val($(this).attr('title'));
			}
		}).keydown(function(e){
			if(e.keyCode == 13){
			$("#button").trigger('click');
				return false;
				}
			});
		
	},
	//点击文本消失
	'textbar':function(){
		$("#button").click(function(){
			var txtVal=$("#textfield");
			if(txtVal.val()==''){
				txtVal.css('color','#000').focus();
			}
		});
	},
	//判断输入框是否与内容符合
	'search_txt':function(w){
		var word = $.trim(w); //去掉空格
		var result = [];
		var i, j, num_match = 0;
		for(i in window.data){
			if(JSON.stringify(window.data[i].question).indexOf((word))!= -1 ||window.data[i].answer.indexOf(word) != -1){
				result.push(window.data[i]);
				num_match++;
			}
		if(num_match>15)
			break;
		}
		return (result);
	},
	'set_txt':function(result){
		searchTools.del();
		var i;
		for(i in result){
			$(".searchBox").append('<ul class="sear-ul"><li class="txtmain-one">' + result[i].question + '</li><li>' + result[i].answer + '</li></ul>');
			searchTools.txtList();
		}
	},
	//搜索完成后删除数据
	'del':function(){
		$(".txtBox ul:gt(0)").remove();
	},
	'txtList':function(){
		$('.searchBox ul:odd li').css('background','#2c3238');
		$('.searchBox ul:even li').css('background','#24292e');
	}
}
searchTools.int();