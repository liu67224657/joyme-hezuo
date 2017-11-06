<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>hao123排行榜</title>
    <link href="" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="./scripts/jquery1.7.1.min.js"></script>
</head>
<body>
	<h1>hao123排行榜</h1>
	<hr>
	<form action="<?php echo isset($data[0]['id']) ? 'index.php?m=rank&p=updatePro' : 'index.php?m=rank&p=addPro';?>" method='post' onsubmit=" return checkData()">
		<table>
			<tr>
				<td>游戏名称：</td>
				<td><input type="text" name="title" id="title" value="<?php echo isset($data[0]['title']) ? $data[0]['title'] : '';?>"/></td>
				<td><span style="color:red">*</span>如：植物大战僵尸。。。</td>
			</tr>
			<tr>
				<td>页面链接：</td>
				<td><input type="text" name="url" id="url" value="<?php echo isset($data[0]['url']) ? $data[0]['url'] : '';?>"/></td>
				<td><span style="color:red">*</span>请输入带http的完整链接</td>
			</tr>
			<tr>
				<td>图片地址：</td>
				<td><input type="text" name="img_url" id="img_url" value="<?php echo isset($data[0]['img_url']) ? $data[0]['img_url'] : '';?>"/></td>
				<td><span style="color:red">*</span>请输入带http的完整链接</td>
			</tr>
			<tr>
				<td>游戏类型：</td>
				<td><input type="text" name="other" id="other" value="<?php echo isset($data[0]['other']) ? $data[0]['other'] : '';?>"/></td>
				<td><span style="color:red">*</span>如：休闲，益智。。。</td>
			</tr>
			<tr>
				<td>游戏排序：</td>
				<td><input type="text" name="serial" id="serial" value="<?php echo isset($data[0]['serial']) ? $data[0]['serial'] : '';?>"/></td>
				<td><span style="color:red">*</span>请输入1-10的排序号</td>
			</tr>
			<tr>
				<td>榜单分类：</td>
				<td>
					<?php if(!isset($data[0]['cat'])):?>
						<input type="radio" name="cat" value="0" checked />网游
						<input type="radio" name="cat" value="1" />单机
					<?php else:?>
						<input type="radio" name="cat" value="0" <?php echo isset($data[0]['cat']) && ($data[0]['cat']==0) ? 'checked' : '';?>/>网游
						<input type="radio" name="cat" value="1" <?php echo isset($data[0]['cat']) && ($data[0]['cat']==1) ? 'checked' : '';?>/>单机
					<?php endif;?>
				</td>
				<td><span style="color:red">*</span>hao123的排行切换大分类</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="hidden" name="id" value="<?php echo isset($data[0]['id']) ? $data[0]['id'] : '';?>">
					<input type="submit" name="hao123" value="提交">
				</td>
			</tr>
		</table>
	</form>
	<script type="text/javascript">
		function checkData(){
			var preg=new RegExp("^[A-Za-z]+://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$");
			if($("#title").val()==''){
				alert('请输入游戏名称');
				return false;
			}
			if($("#url").val()==''){
				alert('请输入正确页面链接');
				return false;
			}else if(!preg.test($("#url").val())){
				alert('请输入正确的带http://的完整页面链接');
				return false;
			}
			if($("#img_url").val()==''){
				alert('请输入正确图片链接');
				return false;
			}else if(!preg.test($("#img_url").val())){
				alert('请输入正确的带http://的完整图片链接');
				return false;
			}
			if($("#other").val()==''){
				alert('请输入游戏类型');
				return false;
			}
			if($("#serial").val()==''){
				alert('请输入游戏序号');
				return false;
			}
			return true;
		}
	</script>
</body>
</html>