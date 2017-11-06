<?php

/**
 * Description of searchService
 * 
 * 
 * @author clarkzhao
 * @date 2014-10-30 01:46:41
 * @copyright joyme.com
 */

require_once 'HttpClient.class.php';
class searchService {

    const SOLR_CORE = 'wikipages';

    protected $httpRequest;

    //put your code here
    public function __construct($host,$port = 80) {
        $this->httpRequest = new HttpClient($host, $port);
//        $this->httpRequest->debug=true;
    }

//  c:core的名称，一个业务一个核
//    q:查询条件,例子：(name|summary:熊)(age:0_20)，name OR summary包含熊 AND age从0-20岁的  
//    sort:排序条件，例子age:<desc、asc>
//    p:分页参数代表当前第几页
//    s:pagesize每页显示多少条记录
    public function query($q, $p = 1, $s = 10, $sort = '') {
        $path = '/search/query.do';
        $data = array(
            'c' => self::SOLR_CORE,
            'q' =>$q,
            'p'=>$p,
            'sort'=>$sort,
           's'=>$s
        );
        $ret = $this->httpRequest->post($path, $data);
        if($ret){
            return  json_decode($this->httpRequest->getContent(),true);
        }
        return $ret;
    }

}
