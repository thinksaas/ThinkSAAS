<?php
defined('IN_TS') or die('Access Denied.');
aac('system')->isLogin();
switch($ts){
	//基本配置
	case "":
		
		include template("admin/options");
		
		break;
}