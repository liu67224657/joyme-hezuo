<?php

/**
 * Description:神马搜索
 * Author: gradydong
 * Date: 2017/3/16
 * Time: 10:15
 * Copyright: Joyme.com
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'app.class.php');
include_once(AROOT . 'lib' . DS . 'PHPExcel' . DS . 'PHPExcel.php');


class smss extends app
{

    public function show()
    {
        set_time_limit(0);
        global $GLOBALS;
        if ($_FILES['tags']) {
            //read excel file
            $xlsxpath = $_FILES['tags']['tmp_name'];
            $objReader = PHPExcel_IOFactory::createReader("Excel2007");
            $objPHPExcel = $objReader->load($xlsxpath);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $excelData = array();
            for ($row = 1; $row <= $highestRow; $row++) {
                $excelstr = (string)$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                if($excelstr){
                    for ($col = 0; $col < $highestColumnIndex; $col++) {
                        $excelData[$row][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                    }
                }else{
                    break;
                }
            }
            $string = $this->getxml();
            $xml = simplexml_load_string($string);
            $datalist = $xml->addChild('datalist');
            $this->adddatalistitem($datalist, $excelData);
            $smxml = $xml->asXML();
            $numtxtpath = AROOT . '../sm/data/num.txt';
            $num = file_get_contents($numtxtpath);
            $smxmlpath = AROOT . '../sm/xml/new' . $num . '.xml';
            file_put_contents($smxmlpath, html_entity_decode($smxml));
            $this->addsitemapitem($num);
            file_put_contents($numtxtpath, ($num + 1));
        }
        render('', 'web', 'smss/index');
    }


    function adddatalistitem($datalist, $excelData)
    {
        if ($excelData) {
            foreach ($excelData as $k => $v) {
                if ($v[0] != 'tagName' && !empty($v[1])) {
                    $tagUrl = $v[1];
                    $imgUrl = $this->getimgurl($tagUrl);
                    $content = $this->http_get($tagUrl);
                    $imglist = $this->getimglist($content);
                    $imgnum = count($imglist);
                    if ($imglist) {
                        foreach ($imglist as $k => $list) {
                            $sort = $k + 1;
                            $item = $datalist->addChild('item');
                            $item->addChild('img', '<![CDATA[ ' . $list['url'] . ' ]]>');
                            $item->addChild('imgUrl', '<![CDATA[ ' . $imgUrl . '#img' . $sort . ' ]]>');
                            $item->addChild('firstCategory', '<![CDATA[ ' . $v[2] . ' ]]>');
                            $item->addChild('secondCategory', '<![CDATA[ ' . $v[3] . ' ]]>');
                            $item->addChild('thirdCategory', '<![CDATA[ ' . $v[4] . ' ]]>');
                            $tagList = $item->addChild('tagList');
                            $tagListitem = $tagList->addChild('item');
                            $tagListitem->addChild('tagName', '<![CDATA[ ' . $v[0] . ' ]]>');
                            $tagListitem->addChild('tagUrl', '<![CDATA[ ' . $tagUrl . ' ]]>');
                            $tagListitem->addChild('sort', '<![CDATA[ ' . $sort . ' ]]>');
                            $item->addChild('above', '<![CDATA[ 0 ]]>');
                            $item->addChild('enable', '<![CDATA[ 1 ]]>');
                            $item->addChild('imgs', '<![CDATA[ 1 ]]>');
                            $item->addChild('imgsNum', '<![CDATA[ '.$imgnum.' ]]>');
                        }
                    }
                }
            }
        }
    }


    function getimglist($content)
    {
        preg_match_all('/<img src=\".*?\" data-url=\"(.*?)\" alt=\".*?\" data-desc=\"(.*?)\" width=\"100%\" \/> /is', $content, $match);
        $img = [];
        if ($match) {
            $imgurls = $match[1];
            $descs = $match[2];
            foreach ($imgurls as $k => $v) {
                $img[$k]['url'] = $v;
                $img[$k]['desc'] = $descs[$k];
            }
        }
        return $img;
    }

    function getimgurl($tagUrl)
    {
        $imgurls = explode('news', $tagUrl);
        return $imgurls[0] . 'news/shenma/news' . $imgurls[1];
    }


    function addsitemapitem($num)
    {
        $sitemapindexpath = AROOT . '../sm/xml/sitemapindex2.xml';
        $string = file_get_contents($sitemapindexpath);
        $xml = simplexml_load_string($string);
        $sitemap = $xml->addChild('sitemap');
        $sitemap->addChild('loc', '<![CDATA[ http://hezuo.joyme.com/sm/xml/new'.$num.'.xml ]]>');
        $sitemap->addChild('lastmod', '<![CDATA[ ' . date("Y-m-d") . ' ]]>');
        $smxml = $xml->asXML();
        file_put_contents($sitemapindexpath, html_entity_decode($smxml));
    }


    function getxml()
    {
        $string = <<<XML
<?xml version='1.0' encoding='UTF-8'?>        
<document>
	<webName>
		<![CDATA[ 着迷网 ]]>
	</webName>
	<hostName>
		<![CDATA[ www.joyme.com ]]>
	</hostName>
</document>
XML;
        return $string;
    }

    protected function http_get($url)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_ENCODING, "gzip");
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 60);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

}