<?php

/**
 * Description of spider
 * 抓取视频网站的信息
 * 
 * @author clarkzhao
 * @date 2014-11-01 12:05:56
 * @copyright joyme.com
 */

date_default_timezone_set('PRC');  
error_reporting(0); // 关闭错误提示

require_once __DIR__ . '/../comm/simple_html_dom.php';
$agent_iphone = 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0_2 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/11A501';
$agent_ipad = 'Mozilla/5.0 (iPad; CPU OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53';
//$tv_domain = 1; //0_其他,1_SOHU,2_IQYI,3_YOUKU,4_TUDOU,5_LETV,6_QQTV   
//应该给一个默认 www的
$agent = $agent_ipad;

//$url = 'http://pad.tv.sohu.com/20140430/n399043939.shtml';
//$url = 'http://v.youku.com/v_show/id_XMzkyODk5NzI0.html';
//http://www.letv.com/ptv/pplay/48548/26.html 
$url = $_REQUEST['url'];
$urlParse = parse_url($url);
$domain_sohu = 'sohu';
$domain_youku = 'youku';
$domain_letv = 'letv';
$domain_tudou = 'tudou';
$domain_iqiyi = 'iqiyi';

$domains = array(
    'sohu' => 1,
    'youku' => 3,
    'letv' => 5,
    'tudou' => 4,
    'iqiyi'=>2
);

$domain = '';
if ($urlParse['host'] == 'pad.tv.sohu.com') {
    $agent = $agent_ipad;
    $domain = $domain_sohu;
}
if ($urlParse['host'] == 'tv.sohu.com') {
    $url = str_replace('tv.sohu.com', 'pad.tv.sohu.com', $url);
    $agent = $agent_ipad;
    $domain = $domain_sohu;
}
if ($urlParse['host'] == 'v.youku.com') {
    $domain = $domain_youku;
}
if ($urlParse['host'] == 'www.letv.com') {
    $domain = $domain_letv;
}
if ($urlParse['host'] == 'www.tudou.com') {
    $domain = $domain_tudou;
}
if ($urlParse['host'] == 'www.iqiyi.com') {
    $url = trim($url);
    $domain = $domain_iqiyi;
}


$Tv = array();
$Tv['m3u8'] = '';
$Tv['play_num'] = 0;
$Tv['favorite_num'] = 0;
$Tv['space'] = '';
$Tv['display_order'] = 0;
$Tv['remove_status'] = 'invalid';
$Tv['update_date'] = date('Y-m-d H:i:s', time());
$Tv['create_date'] = date('Y-m-d H:i:s', time()); //漫画发布时间？
$Tv['create_user'] = 'php-spider';
$Tv['display_order'] = ceil(microtime(true) * 1000);
$Tv['domain'] = $domains[$domain];

$return['rs'] = -1;
$return['result'] = array();
//抓取土豆
if ($domain == $domain_tudou) {

    $htmldatau = @file_get_contents($url);
    $ret = preg_match('/ylid:.*([\d\D]*),/isU', $htmldatau, $matche);
    $Tv['domain_param'] = trim($matche[1]);
    $ret = preg_match('/kw:.*([\d\D]*),/isU', $htmldatau, $matche);
    $Tv['tv_name'] = str_replace("'", "", trim($matche[1]));
    $ret = preg_match('/pic:.*([\d\D]*),/isU', $htmldatau, $matche);
    $Tv['tv_pic'] = str_replace("'", "", trim($matche[1]));
    $Tv['url'] = $url;
    $Tv['tv_number'] = 0;
    $Tv['tags'] = '';
    $return['rs'] = 1;
    $return['result'] = $Tv;
}
//抓取乐视频页
if ($domain == $domain_letv) {
    $htmldatau = @file_get_contents($url);
    $ret = preg_match('/vid:.*([\d\D]*),/isU', $htmldatau, $matche);
    $Tv['domain_param'] = $matche[1];
    $ret = preg_match('/title:".*([\d\D]*)",/isU', $htmldatau, $matche);
    $Tv['tv_name'] = str_replace('"', '', $matche[1]);
    $ret = preg_match('/pPic:".*([\d\D]*)",/isU', $htmldatau, $matche);
    $Tv['tv_pic'] = str_replace('"', '', $matche[1]);
    $Tv['domain'] = 5;
    $Tv['url'] = $url;
    $Tv['tv_number'] = 0;
    $Tv['tags'] = '';
    $return['rs'] = 1;
    $return['result'] = $Tv;
}
//抓取搜狐视频页
if ($domain == $domain_sohu) {
    $htmldatau = curlByIOS($url, $agent);
//    $htmldatau = file_get_contents('pad.sohu.html');
    $ret = preg_match('/"vid":.*([\d\D]*),"videoDesc/isU', $htmldatau, $matche);
    $Tv['domain_param'] = $matche[1];
    $ret = preg_match('/"videoName":.*([\d\D]*),"videoSubName/isU', $htmldatau, $matche);
    $Tv['tv_name'] = str_replace('"', '', $matche[1]);
    $ret = preg_match('/"tvBigPic":.*([\d\D]*),"tvCopyrightId/isU', $htmldatau, $matche);
    $Tv['tv_pic'] = str_replace('"', '', $matche[1]);
    $Tv['domain'] = 1;
    $Tv['url'] = $url;
    $Tv['tv_number'] = 0;
    $Tv['tags'] = '';
    $return['rs'] = 1;
    $return['result'] = $Tv;
}
//抓取优酷视频页
if ($domain == $domain_youku) {
///    file_put_contents('youku.html' ,file_get_contents($url));
//    $htmldatau = file_get_contents('youku.html');
//    $html = new simple_html_dom();
//    $html->load($htmldatau);
    $ret = preg_match('/id_([\d\D]*).html/isU', $url, $matche);
    $vid = $matche[1];
    $dataurl = "http://v.youku.com/player/getPlayList/VideoIDS/{$vid}/timezone/+08/version/5/source/video";
    $datastring = @file_get_contents($dataurl);
    $data = json_decode($datastring, true);
    $data = $data['data'][0];
    $Tv['domain_param'] = $vid;
    $Tv['tv_name'] = $data['title'];
    $Tv['tv_pic'] = $data['logo'];
    $Tv['domain'] = 3;
    $Tv['url'] = $url;
    $Tv['tv_number'] = 0;
    $Tv['tags'] = '';
    $return['rs'] = 1;
    $return['result'] = $Tv;
}
//抓取爱奇艺视频页
if ($domain == $domain_iqiyi) {
    $htmldatau = @file_get_contents($url);
    $ret = preg_match('/v_(.*?).html/isU', $url, $matche);
    $Tv['domain_param'] = $matche[1];
    $ret = preg_match('/<p class=\"mod-add-date\">.*?<span>第(.*?)集<\/span>&nbsp;&nbsp;<span>(.*?)<\/span>.*?<\/p>/is', $htmldatau, $matche);
    $Tv['tv_number'] = $matche[1];
    $Tv['tv_name'] = str_replace('"', '', $matche[2]);
    $ret = preg_match('/content=\'(.*?)\' \/><!--海报图-->/is', $htmldatau, $matche);
    $Tv['tv_pic'] = str_replace('"', '', $matche[1]);
    $Tv['domain'] = 2;
    $Tv['url'] = str_replace('www.iqiyi.com', 'm.iqiyi.com', $url);
    $Tv['tags'] = '';
    $return['rs'] = 1;
    $return['result'] = $Tv;
}



echo json_encode($return);

function curlByIOS($url, $agent = false) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    if ($agent) {
        curl_setopt($curl, CURLOPT_USERAGENT, $agent);
    }
    //模拟苹果设备 
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}
