<?php
/**
 * User: gradydong
 * Date: 2016/1/6
 * Time: 11:10
 */

if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'app.class.php');
include_once(AROOT . 'lib' . DS . 'PHPExcel' . DS . 'PHPExcel.php');

use Joyme\core\Request;
use Joyme\core\Log;

class tkzhgcode extends app
{

    //页面
    public function show()
    {
        render('', 'web', 'activity/tkzhg/code');
    }

    //提交信息，获取礼包码
    public function getcode()
    {

        $callback = Request::getParam('callback'); //jsonp回调参数，必需
        // 第1个问题
        $question1 = Request::getParam('question1');
        if(empty($question1) ||
            !is_numeric($question1)
        ){
            echo $callback . '(' . json_encode(array(
                    'rs' => 0,
                    'msg' => '问题一的答案格式不正确'
                )) . ')';  //返回格式，必需
            exit();
        }
        // 第2个问题
        $question2 = Request::getParam('question2');
        if(empty($question2) ||
            !is_numeric($question2)
        ){
            echo $callback . '(' . json_encode(array(
                    'rs' => 0,
                    'msg' => '问题二的答案格式不正确'
                )) . ')';  //返回格式，必需
            exit();
        }
        // 第3个问题
        $question3 = Request::getParam('question3');
        if(empty($question3) ||
            !is_numeric($question3)
        ){
            echo $callback . '(' . json_encode(array(
                    'rs' => 0,
                    'msg' => '问题三的答案格式不正确'
                )) . ')';  //返回格式，必需
            exit();
        }
        // QQ号
        $qq = Request::getParam('qq');
        if(empty($qq) ||
            !preg_match("/[1-9][0-9]{4,}/",$qq)
        ){
            echo $callback . '(' . json_encode(array(
                    'rs' => 0,
                    'msg' => 'QQ号格式不正确'
                )) . ')';  //返回格式，必需
            exit();
        }
        // 邮箱地址
        $email = Request::getParam('email');
        if(empty($email) ||
            !preg_match("/[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/",$email)
        ){
            echo $callback . '(' . json_encode(array(
                    'rs' => 0,
                    'msg' => '邮箱格式不正确'
                )) . ')';  //返回格式，必需
            exit();
        }

        $host = $GLOBALS['config']['db']['db_host'];
        $username = $GLOBALS['config']['db']['db_user'];
        $password = $GLOBALS['config']['db']['db_password'];

        $conn = mysqli_connect($host, $username, $password) or die('连接数据库出错！');
        mysqli_select_db($conn, 'hezuo');
        mysqli_query($conn, 'BEGIN');
        $rs = mysqli_query($conn, 'select id,code from activity_code where status = 0 order by id ASC limit 1');
        $row = mysqli_fetch_array($rs);
        mysqli_free_result($rs);
        if ($row) {
            $id = $row[0];
            $code = $row[1];
            $sqlUP = "UPDATE mhxy_activity_answer SET status=1,update_time=".time()." WHERE id = " . $id;
            mysqli_query($conn, $sqlUP);
            $affectRow = mysqli_affected_rows($conn);
            if ($affectRow == 0 || mysqli_errno($conn)) {
                // 回滚事务重新提交
                mysqli_query($conn, 'ROLLBACK');
            } else {
                mysqli_query($conn, 'COMMIT');
            }
            mysqli_close($conn);
            echo $callback . '(' . json_encode(array(
                    'rs' => 1,
                    'msg' => $code
                )) . ')';  //返回格式，必需
            exit();
        } else {
            mysqli_query($conn, 'COMMIT');
            mysqli_close($conn);
            echo $callback . '(' . json_encode(array(
                    'rs' => 1,
                    'msg' => '礼包码已领取完！'
                )) . ')';  //返回格式，必需
            exit();
        }
    }

    public function exportexcel()
    {
        set_time_limit(1800);
        $objPHPExcel = new PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        $objProps->setCreator("tkzhg");
        $objProps->setLastModifiedBy("tkzhg");
        $objProps->setTitle("tkzhg");
        $objProps->setSubject("tkzhg Data");
        $objProps->setDescription("tkzhg Data");
        $objProps->setKeywords("tkzhg Data");
        $objProps->setCategory("tkzhg");
        $objPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objPHPExcel->getActiveSheet();

        $objActSheet->setTitle('Sheet1');

        $objPHPExcel->getActiveSheet()->setCellValue('A1','问题1');
        $objPHPExcel->getActiveSheet()->setCellValue('B1','问题2');
        $objPHPExcel->getActiveSheet()->setCellValue('C1','问题3');
        $objPHPExcel->getActiveSheet()->setCellValue('D1','QQ号');
        $objPHPExcel->getActiveSheet()->setCellValue('E1','邮箱');

        $tkzhgcodemodel = M('TkzhgCode');
        $tkzhgcodeLists = $tkzhgcodemodel->getAllLists();
        if($tkzhgcodeLists){
            foreach ($tkzhgcodeLists as $tk => $tkzhgcodeList) {
                //问题一
                $objPHPExcel->getActiveSheet()->setCellValue('A' . ($tk + 1), $tkzhgcodeList['answer1']);
                //问题二
                $objPHPExcel->getActiveSheet()->setCellValue('B' . ($tk + 1), $tkzhgcodeList['answer2']);
                //问题三
                $objPHPExcel->getActiveSheet()->setCellValue('C' . ($tk + 1), $tkzhgcodeList['answer3']);
                //QQ号
                $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . ($tk + 1), $tkzhgcodeList['qq'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->getStyle('D' . ($tk + 1))->getNumberFormat()->setFormatCode("@");
                //邮箱
                $objPHPExcel->getActiveSheet()->setCellValue('E' . ($tk + 1), $tkzhgcodeList['email']);
            }
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        ob_end_clean();
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition:attachment;filename=[坦克指挥官]_" . date('Y-m-d', time()) . ".xls");
        header("Pragma:no-cache");
        header("Expires:0");
        $objWriter->save('php://output');
    }

}