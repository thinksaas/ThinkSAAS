<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "":
	
		$title = '更换主题';

		$arrTheme	= dirList('theme');

		include template("theme");
		
		break;
	
	//执行
	case "do":
		$theme = $_POST['site_theme'];
		setcookie("ts_theme", $theme, time()+3600*30,'/');   
		
		qiMsg("主题更换成功！");
		break;
}