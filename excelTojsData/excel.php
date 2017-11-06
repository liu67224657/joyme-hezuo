<?php
header('Content-type:text/html;charset=utf-8');
error_reporting(E_ALL);
require_once 'Classes/PHPExcel.php';
$fileName = '/opt/wiki/wiki/uploadwikitpl/uploadfile/dd.xlsx';
$path = '';
$filePath = $path.$fileName;
$PHPExcel = new PHPExcel();        
$PHPReader = new PHPExcel_Reader_Excel2007();
$res = $PHPReader->canRead($filePath);
//为了可以读取所有版本Excel文件
if(!$PHPReader->canRead($filePath))
{                        
    $PHPReader = new PHPExcel_Reader_Excel5();    
    if(!$PHPReader->canRead($filePath))
    {                        
        echo '未发现Excel文件！';
        return;
    }
}
 
//不需要读取整个Excel文件而获取所有工作表数组的函数，感觉这个函数很有用，找了半天才找到
$sheetNames  = $PHPReader->listWorksheetNames($filePath);
 
//读取Excel文件
$PHPExcel = $PHPReader->load($filePath);
 
//获取工作表的数目
$sheetCount = $PHPExcel->getSheetCount();
 
//选择第一个工作表
$currentSheet = $PHPExcel->getSheet(0);
 
//取得一共有多少列
$allColumn = $currentSheet->getHighestColumn();   
 
//取得一共有多少行
$allRow = $currentSheet->getHighestRow();  
 
//数据存储数组
$data = array();
 
//循环读取数据，默认编码是utf8，
for($currentRow = 1;$currentRow<=$allRow;$currentRow++)
{
    for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++)
    {
        $address = $currentColumn.$currentRow;
		$data[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
        //echo iconv( 'utf-8','gbk', $currentSheet->getCell($address)->getValue() )."\t";
    }
}
var_dump($data);
?>