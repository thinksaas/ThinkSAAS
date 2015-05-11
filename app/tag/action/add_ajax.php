<?php 
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "":
		
		$objname = tsUrlCheck($_GET['objname']);
		$idname = tsUrlCheck($_GET['idname']);
		$objid = intval($_GET['objid']);
		
		include template("add_ajax");
		break;
		
	case "do":
	
		$objname = tsUrlCheck($_POST['objname']);
		$idname = tsUrlCheck($_POST['idname']);
		$objid = intval($_POST['objid']);
		$tags = t($_POST['tags']);
		
		$new['tag']->addTag($objname,$idname,$objid,$tags);
		
		echo "<script language=JavaScript>parent.window.location.reload();</script>";
	
		break;
}