<?php
/**
 * Created by PhpStorm.
 * User: kexuedong
 * Date: 2015/12/21
 * Time: 10:23
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'app.class.php');
include_once(AROOT . 'lib' . DS . 'PHPExcel' . DS . 'PHPExcel.php');

use Joyme\core\Request;
use Joyme\core\Log;

class keyword extends app
{

    //页面
    public function show()
    {
        set_time_limit(1800);
        $keyword = Request::post("keyword");//关键字
        $check1 = Request::post("check1");//字母
        $check2 = Request::post("check2");//数字
        $check3 = Request::post("check3");//子项
        $fetchType = Request::post("fetchType");//pc
        $data = array();
        if($keyword){
            $kwmodel = M('KeyWord');
            if($fetchType){
                $kwmodel->fetchType = $fetchType;
            }

            //选择字母
            if ($check1 == 'letter'
                && empty($check2)
            ) {
                $longtails = $kwmodel->choices['letter'];
            }
            //选择数字
            elseif (empty($check1)
                && $check2 == 'number'
            ) {
                $longtails = $kwmodel->choices['number'];
            }
            //选择字母和数字
            elseif ($check1 == 'letter'
                && $check2 == 'number'
            ){
                $longtails = array_merge($kwmodel->choices['letter'], $kwmodel->choices['number']);
            }
            elseif ($check3 == 'child'){
                $longtails = array_merge($kwmodel->choices['letter'], $kwmodel->choices['number']);
            }
            else {
                $longtails = array();
            }
            if($longtails){
                //内容
                foreach ($longtails as $longtail) {
                    $v = $keyword . $longtail;
                    $results = $kwmodel->fetch($v);
                    $data[] = '【' . $keyword . '】' . strtoupper($longtail) . '类长尾词集合';
                    foreach ($results as $result) {
                        $data[] = $result;
                        //子项
                        if($check3 == 'child') {
                            $rs = $kwmodel->fetch($result);
                            if ($rs) {
                                foreach ($rs as $r) {
                                    $data[] = '--' . $r;
                                }
                            }
                        }
                    }
                }
            }else{
                $data = $kwmodel->fetch($keyword);
            }
        }
        render($data, 'web', 'keyword/index');
    }

    public function exportexcel()
    {
        set_time_limit(1800);
        $objPHPExcel = new PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        $objProps->setCreator("keyword");
        $objProps->setLastModifiedBy("keyword");
        $objProps->setTitle("keyword");
        $objProps->setSubject("keyword Data");
        $objProps->setDescription("keyword Data");
        $objProps->setKeywords("keyword Data");
        $objProps->setCategory("keyword");
        $objPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objPHPExcel->getActiveSheet();

        $objActSheet->setTitle('Sheet1');

        $keyword = Request::get("keyword");//关键字
        $check1 = Request::get("check1");//字母
        $check2 = Request::get("check2");//数字
        $check3 = Request::get("check3");//子项
        $fetchType = Request::get("fetchType");//pc
        $data = array();
        if($keyword){
            $kwmodel = M('KeyWord');
            if($fetchType){
                $kwmodel->fetchType = $fetchType;
            }
            //选择字母
            if ($check1 == 'letter'
                && empty($check2)
            ) {
                $longtails = $kwmodel->choices['letter'];
            }
            //选择数字
            elseif (empty($check1)
                && $check2 == 'number'
            ) {
                $longtails = $kwmodel->choices['number'];
            }
            //选择字母和数字
            elseif ($check1 == 'letter'
                && $check2 == 'number'
            ){
                $longtails = array_merge($kwmodel->choices['letter'], $kwmodel->choices['number']);
            }
            elseif ($check3 == 'child'){
                $longtails = array_merge($kwmodel->choices['letter'], $kwmodel->choices['number']);
            }
            else {
                $longtails = array();
            }
            if($longtails){
                //内容
                foreach ($longtails as $longtail) {
                    $v = $keyword . $longtail;
                    $results = $kwmodel->fetch($v);
                    $data[] = '【' . $keyword . '】' . strtoupper($longtail) . '类长尾词集合';
                    foreach ($results as $result) {
                        $data[] = $result;
                        //子项
                        if($check3 == 'child') {
                            $rs = $kwmodel->fetch($result);
                            if ($rs) {
                                foreach ($rs as $r) {
                                    $data[] = '--' . $r;
                                }
                            }
                        }
                    }
                }
            }else{
                $data = $kwmodel->fetch($keyword);
            }
        }

        foreach($data as $dk => $dt){
            $objPHPExcel->getActiveSheet()->setCellValue('A'.($dk+1), $dt);
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        ob_end_clean();
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition:attachment;filename=[".$keyword."]_" . date('Y-m-d', time()) . ".xls");
        header("Pragma:no-cache");
        header("Expires:0");
        $objWriter->save('php://output');
    }

}