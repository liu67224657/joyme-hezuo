<?php
/**
 * Description of uploadqiniu
 * 
 * 上传文件到7牛 app!
 * @author clarkzhao
 * @date 2014-12-01 10:50:56
 * @copyright joyme.com
 */
require_once("../comm/qiniu/rs.php");
require_once("../comm/qiniu/io.php");
require_once("../comm/qiniu/fop.php");

$bucket = 'joymeapp';
$accessKey = 'G8_5kjfXfaufU53Da4bnGQ3YP-dhdmqct9sR6ImI';
$secretKey = 'KXwyeZMxYnsZMqAwojI_IEDkYj69zkwvu8jZP5_a';
Qiniu_SetKeys($accessKey, $secretKey);
$putPolicy = new Qiniu_RS_PutPolicy($bucket);
$upToken = $putPolicy->Token(null);
?>
<?php
if($_SERVER['HTTP_HOST']!=='hezuo.enjoyf.com'){
    header("http/1.1 403 Forbidden"); 
    exit;
}

if (!empty($_FILES)) {
    if ($_FILES["file"]["error"] == 0) {
        $key1 = $_FILES["file"]["name"];
        $file = $_FILES["file"]["tmp_name"];
        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1;
        list($ret, $err) = Qiniu_PutFile($upToken, $key1, $file, $putExtra);
        if ($err !== null) {
            echo $err;
            exit;
        } else {
            echo "URL:<br/>"."http://joymeapp.joyme.com/" . $ret['key'];
        }
//        echo "<br/>".$_FILES['file']['name'] . ' upload success';
    }
}else{
    if(isset($_FILES["file"]["error"])){
        var_dump('$_FILES["file"]["error"] '.$_FILES["file"]["error"]);
    }
}
?>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="file" />
    <br/>
    <input type="submit" name="submit" value="Upload">
</form>