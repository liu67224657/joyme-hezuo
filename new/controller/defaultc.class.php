<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

class defaultc extends app
{
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
        render('','web','index');
    }
}
	