<?php
    header("Content-type: text/html; charset=utf-8");
    $filename = "beginTime.txt";
    if($_POST){
        $timefile = fopen($filename,"a");
        $a = empty($_POST['timeshi'])?00:$_POST['timeshi'];
        $b = empty($_POST['timefen'])?00:$_POST['timefen'];
        $c = empty($_POST['timemiao'])?00:$_POST['timemiao'];
        $str = $_POST['timenian']."-".$_POST['timeyue']."-".$_POST['timeri']." ".$a.":".$b.":".$c;
        $shijian = strtotime($str);
        $txt = '';
        file_put_contents($filename, $txt);
        fwrite($timefile,$shijian);
        header("Location:http://".$_SERVER['HTTP_HOST']."/nhtp/settime.php");
    }else{
        $timefile = fopen($filename,"r");
        $line_votes=fgets($timefile); /*读出已经记录的投票结果*/
        if($line_votes){
            echo "<font color='red'>当前设置的时间为</font>：<b>".date("Y-m-d H:i:s",$line_votes)."</b>";
        }else{
            echo "<font color='red'>当前是默认时间2015年3月28日21点</font>";
        }
    }
?>

<form method="post" action="<?php echo 'http://'.$_SERVER['HTTP_HOST']?>/nhtp/settime.php">
    <table border="1">
        <tr>
            <td colspan="2">设置投票开始时间</td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="text" name="timenian" style="width: 50px;">年
                <input type="text" name="timeyue"  style="width: 50px;">月
                <input type="text" name="timeri"   style="width: 50px;">日
                <input type="text" name="timeshi"  style="width: 50px;">时
                <input type="text" name="timefen"  style="width: 50px;">分
                <input type="text" name="timemiao" style="width: 50px;">秒
            </td>
        </tr>
        <tr>
            <td align="center"><input type="submit"></td>
            <td align="center"><input type="reset"></td>
        </tr>
    </table>
</form>


