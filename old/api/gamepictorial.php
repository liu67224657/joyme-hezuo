<?php

/**
 * Description of gamepictorial
 *  手游画报的下载链接
 * 
 * @author clarkzhao
 * @date 2014-11-14 02:12:56
 * @copyright joyme.com
 */
function get_device_type() {
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $type = 'other';
    if (strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
        $type = 'ios';
    }
    if (strpos($agent, 'android')) {
        $type = 'android';
    }
    return $type;
}

$channel = $_REQUEST['channel'];

$os = get_device_type();

if ($os == 'ios') {
    if (empty($channel)) {
//        header("Location:itms-services://?action=download-manifest&url=https://dn-joymeapp.qbox.me/gamepictorial-1.4.0.plist");
        $buffer = '<!DOCTYPE html>';
        $buffer .='<html><head>';
        $buffer .='<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $buffer .='<script >window.location.href = "itms-services://?action=download-manifest&url=https://dn-joymeapp.qbox.me/gamepictorial-1.4.0.plist";</script>';
        $buffer .='</head><body></body></html>';
        echo $buffer;
    }
    if ($channel = 'appstore') {
        header("Location:http://itunes.apple.com/cn/app/shou-you-hua-bao/id830230372?mt=8");
    }
}
if ($os == 'android') {
    header("Location:http://joymeapp.qiniudn.com/gamepictorial-1.4.0.apk");
}
if ($os == 'other') {
    echo "web url";
}
