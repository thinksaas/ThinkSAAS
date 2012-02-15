<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	//列表 
	case "list":
	
		$arrModel = $db->findAll('apple_model');
	
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
		
		$strModel = $db->find('apple_model',array('modelid'=>$modelid));
		
		include template('admin/model_edit');
		break;
		
	//编辑执行
	case "edit_do":
		
		$modelid = intval($_POST['modelid']);
		$modelkey = trim($_POST['modelkey']);
		$modelname = trim($_POST['modelname']);
		
		$db->update('apple_model',array(
			'modelkey'=>$modelkey,
			'modelname'=>$modelname,
		),array(
			'modelid'=>$modelid,
		));
		
		qiMsg("修改成功！");
		
		break;
}