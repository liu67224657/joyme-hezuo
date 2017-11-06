<?php

if( !defined('IN') ) die('bad request');

class core
{
    function __construct() {

    }
    public static function  outputTokenFail($callback=''){

        $data = array('rs' => 1, 'msg' => "token fail", 'result' => 'fail');
        self::outputFormat($data,$callback);
    }

    //返回执行结果
    public static function outputResult($result,$callback='',$num=''){

        if($result){
            $data = array('rs' => 0, 'msg' => "successful operation", 'result' => $num);
        }else{
            $data = array('rs' => 2, 'msg' => "operation failure", 'result' => 'fail');
        }
        self::outputFormat($data,$callback);
    }

    //返回机会结果
    public static function outputOpportunity($callback='',$repeat=false){

        if($repeat){
            $data = array('rs' => 4, 'msg' => "Can't repeat", 'result' => 'fail');
        }else{
            $data = array('rs' => 3, 'msg' => "opportunity used up", 'result' => 'fail');
        }
        self::outputFormat($data,$callback);
    }

    //空参数返回
    public static function optPutEmptyParam($callback=''){

        $data = array('rs' => 5, 'msg' => "empty parameters", 'result' => 'fail');
        self::outputFormat($data,$callback);
    }

    //返回数据格式
    public static function outputFormat($data,$callback){

        if (strlen($callback) > 1) {
            echo $callback . "([" . json_encode($data) . "])";
        } else {
            echo json_encode($data);
        }
        exit;
    }
}

//实例化Model
function M($model_name){

    $model_name = $model_name.'Model';
    //先判断类是否存在
    if(class_exists($model_name)){
        $_model = new $model_name();
        return $_model;
    }
    $suffix_name = '.class.php';
    $model_file = AROOT . 'model'  . DS . $model_name.$suffix_name;
    if(file_exists($model_file)){
        include_once($model_file);
        $_model = new $model_name();
        return $_model;
    }
}




