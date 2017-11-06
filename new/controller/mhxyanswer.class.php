<?php
/**
 * Created by PhpStorm.
 * User: kexuedong
 * Date: 2015/12/17
 * Time: 10:23
 */
if (!defined('IN')) die('bad request');
include_once(AROOT . 'controller' . DS . 'app.class.php');

use Joyme\core\Request;
use Joyme\core\Log;

class mhxyanswer extends app
{
    //三套题，六个题目
    public $answers = array(
        'a' => array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '3',
            '6' => '5',
        ),
        'b' => array(
            '1' => '4',
            '2' => '1',
            '3' => '1',
            '4' => '3',
            '5' => '4',
            '6' => '5',
        ),
        'c' => array(
            '1' => '4',
            '2' => '2',
            '3' => '1',
            '4' => '6',
            '5' => '5',
            '6' => '1',
        ),
    );

    //页面
    public function show()
    {
        render('', 'web', 'activity/mhxy/answer');
    }

    //检查答案是否正确
    public function getanswer()
    {
        $casenum = Request::getParam('casenum'); //第几套题
        $questionsnum = Request::getParam('questionsnum'); // 第几个问题
        $answernum = Request::getParam('answernum'); //选中的答案
        $callback = Request::getParam('callback'); //jsonp回调参数，必需

        if (empty($casenum) || empty($questionsnum) || empty($answernum)) {
            echo $callback . '(' . json_encode(array(
                    'rs' => 0,
                    'msg' => '参数不正确'
                )) . ')';  //返回格式，必需
            exit();
        } else {
            if ($this->answers[$casenum]) {
                if ($this->answers[$casenum][$questionsnum]) {
                    $answer = $this->answers[$casenum][$questionsnum];
                    if ($answernum != $answer) {
                        echo $callback . '(' . json_encode(array(
                                'rs' => 0,
                                'msg' => '选择不正确'
                            )) . ')';  //返回格式，必需
                        exit();
                    } else {
                        if($questionsnum != '6'){
                            echo $callback . '(' . json_encode(array(
                                    'rs' => 1,
                                    'msg' => '选择正确'
                                )) . ')';  //返回格式，必需
                            exit();
                        }else{
                            $host = $GLOBALS['config']['db']['db_host'];
                            $username = $GLOBALS['config']['db']['db_user'];
                            $password = $GLOBALS['config']['db']['db_password'];

                            $conn = mysqli_connect($host, $username, $password) or die('连接数据库出错！');
                            mysqli_select_db($conn, 'hezuo');
                            mysqli_query($conn, 'BEGIN');
                            $rs = mysqli_query($conn, 'select id,code from mhxy_activity_answer where status = 0 order by id ASC limit 1');
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
                    }
                } else {
                    echo $callback . '(' . json_encode(array(
                            'rs' => 0,
                            'msg' => '参数不正确'
                        )) . ')';  //返回格式，必需
                    exit();
                }
            } else {
                echo $callback . '(' . json_encode(array(
                        'rs' => 0,
                        'msg' => '参数不正确'
                    )) . ')';  //返回格式，必需
                exit();
            }
        }
    }

}