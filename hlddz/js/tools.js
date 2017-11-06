var searchTools={ 
	'int':function(){
		searchTools.touch();
		searchTools.intSet();
		},
	'intSet':function(){
		var value=$("#textfield").val()
		$("#textfield").on('focus',function(){
            $(this).val('');
		})
		$("#textfield").on('blur',function(){
			if($('#textfield').val()===''){
			   $(this).val(value);
			}
		})
	},
	'touch':function(){
		$(".inpBtn-box").on('touchstart',function(){
			var w = $("#textfield").val();
			if(w==""){
				console.log("您没有输入要搜索的内容")
			}else{
				$('.search-tit').hide();
				$('.search-result-box').empty();
                searchTools.search_txt(w);
			}
		})
		
	},
	//判断输入框是否与内容符合
	'search_txt':function(w){
		var word =$.trim(w);//去掉空格
		var result = [];
		var i, j, num_match = 0;
		var check=true;
		for(i in info){
			if(info[i].cont.indexOf(word)!= -1 || info[i].key.indexOf(word) != -1){
				var info_list=info[i].cont+','+info[i].url
				result.push(info_list);
				check=false;
				$('.no-result').hide();
			   num_match++;
			}else if(check){
				$('.no-result').show();
			}
			
		if(num_match>15)
			break;
		}

       searchTools.set_txt(result);

	},
	'set_txt':function(result){
		var n;
		for(n in result){
			var IndexOf=result[n].lastIndexOf(',');
			var url_cont=result[n].substr(IndexOf+1);
			var cont_text=result[n].substr(0,IndexOf);
			$(".search-result-box").append('<a href="'+url_cont+'">'+cont_text+'</a>');
			//searchTools.del();	
		}
		
	},
	//搜索完成后删除数据
	'del':function(){
		$(".search-result-box a:gt(0)").remove();
	}
}
searchTools.int();