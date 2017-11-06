<?php
/**
 * Created by PhpStorm.
 * User: gradydong
 * Date: 2015/12/21
 * Time: 10:04
 */
if (!defined('IN'))
    die('bad request');

use Joyme\db\JoymeModel;

class KeyWordModel extends JoymeModel
{

    public $choices = array(
        'letter' => array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'),
        'number' => array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9')
    );

    public $fetchType = 'pc';

    public $fetchurl = array(
        'pc' => 'http://suggestion.baidu.com/su?wd=',
        'm' => 'https://m.baidu.com/su?wd=',
    );

    public function fetch($val)
    {
        if($this->fetchType == 'pc'){
            $url = $this->fetchurl['pc']. urlencode($val);
        }elseif ($this->fetchType == 'm'){
            $url = $this->fetchurl['m'] . urlencode($val);
        }else{
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT,60);

        //执行并获取HTML文档内容
        $str = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        if($str!==false){
            preg_match('/window\.baidu\.sug\(\{.*?s:\[(.*?)\]\}\);/is', $str, $match);
            if ($match[1]) {
                $str = iconv('gbk', 'utf-8', $match[1]);
                $str = preg_replace('/\"/', '', $str);
                return explode(',', $str);
            } else {
                return null;
            }
        }else{
            return null;
        }
    }
}