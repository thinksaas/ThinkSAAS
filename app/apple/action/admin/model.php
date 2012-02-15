<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	//列表 
	case "list":
	
		$arrModel = $db->fetch_all_assoc("select * from ".dbprefix."apple_model");
	
		include template('admin/model_list');
		break;
		
	//添加 
	case "add":
		
		include template('admin/model_add');
		break;
		
	//执行 
	case "add_do":
		$modelkey = trim($_POST['modelkey']);
		$modelname = trim($_POST['modelname']);
		
		$db->create('apple_model',array(
			'modelkey'=>$modelkey,
			'modelname'=>$modelname,
		));
		
		header("Location: ".SITE_URL.'index.php?app=apple&ac=admin&mg=model&ts=list');
		break;
		
	//编辑 
	case "edit":
		$modelid = intval($_GET['modelid']);
		
		$strModel = $db->once_fetch_assoc("select * from ".dbprefix."apple_model where `modelid`='$modelid'");
		
		include template('admin/model_edit');
		break;
		
	//编辑执行
	case "edit_do":
		
		$modelid = intval($_POST['modelid']);
		$modelkey = trim($_POST['modelkey']);
		$modelname = trim($_POST['modelname']);
		
		$db->query("update ".dbprefix."apple_model set `modelkey`='$modelkey',`modelname`='$modelname' where `modelid`='$modelid'");
		
		qiMsg("修改成功！");
		
		break;
}