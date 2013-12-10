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
	
		$objname = t($_POST['objname']);
		$idname = t($_POST['idname']);
		$objid = t($_POST['objid']);
		$tags = t($_POST['tags']);
		
		$new['tag']->addTag($objname,$idname,$objid,$tags);
		
		echo "<script language=JavaScript>parent.window.location.reload();</script>";
	
		break;
}