<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>互联网世界-投票结果</title>
</head>
<style>
    .bt1,.bt2,.bt3{  overflow: hidden;float: left; margin-left: 25px;}
</style>

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xinshi
 * Date: 15-1-19
 * Time: 上午10:57
 * To change this template use File | Settings | File Templates.
 */

$file = @fopen("vote_logo.txt","r");
if(!$file){
    echo "还没有投票记录，请稍后查看！！！";die;
}
$forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);
if(preg_match("/mobile/",$forasp)){
    die("只能通过PC端访问！");
}
$line_votes=fgets($file); /*读出已经记录的投票结果*/
$single_vote=explode("|", $line_votes);
$people = count($single_vote)-2;

$vote = array();
$result = array();
for ($i=0; $i<=$people; $i++)
{
    $vote[] = explode("?",$single_vote[$i]);
}
foreach($vote as $k=>$v){
    $result[] = $v[1];
}

$bnt1 = array();
$bnt2 = array();
$bnt3 = array();
foreach($result as $k=>$v){
    $bn1 = explode("/",$v);
    $bnt1[] = $bn1[0];
    $bnt2[] = $bn1[1];
    $bnt3[] = $bn1[2];
}

//最佳原创
$str1 = str_replace("/",",",implode(",",$bnt1));
$vote_num1 = array_filter(explode(",",$str1));
$result1 = array_count_values($vote_num1);

//最佳观点
$str2 = str_replace("/",",",implode(",",$bnt2));
$vote_num2 = array_filter(explode(",",$str2));
$result2 = array_count_values($vote_num2);

//学以致用
$str3 = str_replace("/",",",implode(",",$bnt3));
$vote_num3 = array_filter(explode(",",$str3));
$result3 = array_count_values($vote_num3);

arsort($result1);
arsort($result2);
arsort($result3);
$html = "<h1 style='color: red;'>互联网时代观后感投票评选:</h1>";
echo $html;
echo "<h3>(共有".count($result)."人参与了投票)</h3>";
$html1 = "<table border='1' class='bt1' style='width: 400px;'><tr><td colspan='2' align='center'><h3>最佳原创作品投票结果</h3></td></tr><tr><td align='center'>文章编号</td><td align='center'>投票数量</td></td></tr>";
foreach($result1 as $k=>$v){
    $html1.="<tr><td align='center'>".$k."</td><td align='center'>".$v."</td></tr>";
}
$html1.="</tr></table>";
echo $html1;

$html2 = "<table border='1' class='bt2' style='width: 400px;'><tr><td colspan='2' align='center'><h3>最佳观点作品投票结果</h3></td></tr><tr><td align='center'>文章编号</td><td align='center'>投票数量</td></td></tr>";
foreach($result2 as $k=>$v){
    $html2.="<tr><td align='center'>".$k."</td><td align='center'>".$v."</td></tr>";
}
$html2.="</tr></table>";
echo $html2;

$html3 = "<table border='1' class='bt3' style='width: 400px;'><tr><td colspan='2' align='center'><h3>学以致用作品投票结果</h3></td></tr><tr><td align='center'>文章编号</td><td align='center'>投票数量</td></td></tr>";
foreach($result3 as $k=>$v){
    $html3.="<tr><td align='center'>".$k."</td><td align='center'>".$v."</td></tr>";
}
$html3.="</tr></table>";
echo $html3;
?>


