<?php
if( !defined('IN') ) die('bad request');
include_once( CROOT . 'controller' . DS . 'core.class.php' );
/**
 * Created by JetBrains PhpStorm.
 * User: xinshi
 * Date: 15-4-15
 * Time: 下午2:51
 * To change this template use File | Settings | File Templates.
 */
class app extends core
{
	function __construct()
	{
		// 载入默认的
		parent::__construct();
	}

}
