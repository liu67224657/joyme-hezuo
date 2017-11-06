<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<title>hao123排行榜列表</title>
    <link href="" type="text/css" rel="stylesheet">
</head>
<body>
	<h1>hao123排行榜</h1>
	<hr>
	<table style="float:left;">
		<tr>
			<th>ID</th>
			<th>游戏名称</th>
			<th>图片地址</th>
			<th>游戏类型</th>
			<th>大分类</th>
			<th>排序</th>
			<th>操作</th>
		</tr>
		<?php foreach($data as $v):?>
		<?php if($v['cat']==0):?>
		<tr>
			<td><?php echo $v['id'];?></td>
			<td><a href='<?php echo $v['url'];?>' target="_blank"><?php echo $v['title'];?></a></td>
			<td><img src='<?php echo $v['img_url'];?>' width="50" height="50"/></td>
			<td><?php echo $v['other'];?></td>
			<td><?php echo $cat[$v['cat']];?></td>
			<td><?php echo $v['serial'];?></td>
			<td><a href="index.php?m=rank&p=update&id=<?php echo $v['id']?>">更新</a>  <a href="index.php?m=rank&p=del&id=<?php echo $v['id']?>">删除</a></td>
		</tr>
		<?php endif;?>
		<?php endforeach;?>
	</table>
	<table style="float:left;margin-left:20px;">
		<tr>
			<th>ID</th>
			<th>游戏名称</th>
			<th>图片地址</th>
			<th>游戏类型</th>
			<th>大分类</th>
			<th>排序</th>
			<th>操作</th>
		</tr>
		<?php foreach($data as $v):?>
		<?php if($v['cat']==1):?>
		<tr>
			<td><?php echo $v['id'];?></td>
			<td><a href='<?php echo $v['url'];?>' target="_blank"><?php echo $v['title'];?></a></td>
			<td><img src='<?php echo $v['img_url'];?>' width="50" height="50"/></td>
			<td><?php echo $v['other'];?></td>
			<td><?php echo $cat[$v['cat']];?></td>
			<td><?php echo $v['serial'];?></td>
			<td><a href="index.php?m=rank&p=update&id=<?php echo $v['id']?>">更新</a>  <a href="index.php?m=rank&p=del&id=<?php echo $v['id']?>">删除</a></td>
		</tr>
		<?php endif;?>
		<?php endforeach;?>
	</table>
</body>
</html>