<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "":
	
		$title = '更换主题';

		$arrTheme	= tsScanDir('theme');

		include template("theme");
		
		break;
}