<?php 
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "":
		
		$objname = tsFilter($_GET['objname']);
		$idname = tsFilter($_GET['idname']);
		$objid = intval($_GET['objid']);
		
		include template("add_ajax");
		break;
		
	case "do":
	
		$objname = tsFilter($_POST['objname']);
		$idname = tsFilter($_POST['idname']);
		$objid = intval($_POST['objid']);
		$tags = t($_POST['tags']);
		
		$new['tag']->addTag($objname,$idname,$objid,$tags);
		
		tsNotice('标签添加成功！');
	
		break;
}