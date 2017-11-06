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

if (!empty($_FILES)) {
    if ($_FILES["file"]["error"] == 0) {
        $key1 = $_FILES["file"]["name"];
        $file = $_FILES["file"]["tmp_name"];
        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1;
        list($ret, $err) = Qiniu_PutFile($upToken, $key1, $file, $putExtra);
        if ($err !== null) {
            echo json_encode(array('rs'=>0, 'url'=>'', 'err'=>$err));
            exit;
        } else {
			echo json_encode(array('rs'=>1, 'url'=>'http://joymeapp.joyme.com/' . $ret['key']));
        }
    }
}else{
    if(isset($_FILES["file"]["error"])){
		echo json_encode(array('rs'=>0, 'url'=>'', 'err'=>$_FILES["file"]["error"]));
    }
}
?>