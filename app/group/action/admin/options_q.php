<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 配置选项
 */	

switch($ts){
	
	case "do":
	    $val['q']=$_POST['q'];
		$val['a']=$_POST['a'];
		$db -> insertArr($val,"".dbprefix."veri");
		break;
}