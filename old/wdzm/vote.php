<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xinshi
 * Date: 15-1-19
 * Time: 上午9:27
 * To change this template use File | Settings | File Templates.
 */
$forasp =  strtolower($_SERVER['HTTP_USER_AGENT']);
if(preg_match("/mobile/",$forasp)){
    die("只能通过PC端访问！");
}
//接受访问IP号
$ip = $_SERVER["REMOTE_ADDR"];
//是否有投票记录，默认没有
$Record = false;
//返回结果，字符串
$result = "";
//记录IP文件
$ipfile = "ip_logo.txt";
//投票结果记录
$datafile = "vote_logo.txt";
//接受选中数据
$btn1 = !empty($_POST['vote_code'])?$_POST['vote_code']:false;    //作品

$flag = $_POST['flag'];

$vote_code = explode("-",$btn1);
//去除数组最后一个空元素
array_pop($vote_code);

if($flag==='joymevote'){
    //获取IP记录
    $fileIP = @fopen($ipfile,"r");
    $line_votes=@fgets($fileIP); /*读出已经记录的投票结果*/

    $single_vote=explode("|", $line_votes);

    for ($i=0; $i<=count($single_vote)-1; $i++)
    {
        $arrip = explode("-",$single_vote[$i]);
        if(in_array($ip,$arrip)){
            $Record = true;
            break;
        }
    }
    //如果有记录
    if($Record){
        //您已经投过票了！
        $result = "NO";
    }else{
        //添加投票结果
        ob_start();
        $data = @fopen($datafile,"a");
        $ipinfo = @fopen($ipfile,"a");
        //将IP写入记录
        fwrite($ipinfo, date("Y-m-d H:i:s",time())."-".$ip."|");
        //将结果写入文件
        if($vote_code[1]){
            fwrite($data, date("Y-m-d H:i:s",time())."-".$ip."?".$vote_code[0]."/".$vote_code[1]."|");
        }else{
            fwrite($data, date("Y-m-d H:i:s",time())."-".$ip."?".$vote_code[0]."|");
        }
        fclose($data);//写入成功，关闭文件
        fclose($ipinfo);//写入成功，关闭文件
        ob_clean();
        $result = "YES";
    }
}else{
    //非法投票
    $result = "DANGER";
}

//echo $result;die;
echo $result;die;