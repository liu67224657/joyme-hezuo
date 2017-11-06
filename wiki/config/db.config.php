<?php
if($GLOBALS['domain'] == 'com'){

    $GLOBALS['config']['db']['db_host'] = 'rm-2ze39mi5sfts0i9f4.mysql.rds.aliyuncs.com';
    $GLOBALS['config']['db']['db_port'] = 3306;
    $GLOBALS['config']['db']['db_user'] = 'wikicr';
    $GLOBALS['config']['db']['db_password'] = 'bscIM5yK';
    $GLOBALS['config']['db']['db_name'] = 'jwikiadmin';

}elseif($GLOBALS['domain'] == 'beta'){

    $GLOBALS['config']['db']['db_host'] = 'alyweb002.prod';
    $GLOBALS['config']['db']['db_port'] = 3306;
    $GLOBALS['config']['db']['db_user'] = 'wikipage';
    $GLOBALS['config']['db']['db_password'] = 'wikipage';
    $GLOBALS['config']['db']['db_name'] = 'jwikiadmin';

}else{

    $GLOBALS['config']['db']['db_host'] = '172.16.75.32';
    $GLOBALS['config']['db']['db_port'] = 3306;
    $GLOBALS['config']['db']['db_user'] = 'root';
    $GLOBALS['config']['db']['db_password'] = '123456';
    $GLOBALS['config']['db']['db_name'] = 'jwikiadmin';
}