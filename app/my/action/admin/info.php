<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

	//列表 
	case "list":
	
		$arrInfo = $new['home']->findAll('home_info');
	
		include template('admin/info_list');
		break;
	
	//添加 
	case "add":
		
		include template('admin/info_add');
		break;
		
	case "adddo":
	
		$infokey = $_POST['infokey'];
		$title = $_POST['title'];
		$content = $_POST['content'];
		
		$new['home']->create('home_info',array(
		
			'infokey'=>$infokey,
			'title'=>$title,
			'content'=>$content,
		
		));
		
		header('Location: '.SITE_URL.'index.php?app=home&ac=admin&mg=info&ts=list');
	
		break;
		
	//编辑 
	case "edit":
	
		$infoid = intval($_GET['infoid']);
		$strInfo = $new['home']->find('home_info',array(
		
			'infoid'=>$infoid,
		
		));
	
	
		include template('admin/info_edit');
	
		break;
		
	case "editdo":
	
		$infoid = intval($_POST['infoid']);
		$infokey = trim($_POST['infokey']);
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		
		$new['home']->update('home_info',array(
		
			'infoid'=>$infoid,
		
		),array(
		
			'infokey'=>$infokey,
			'title'=>$title,
			'content'=>$content,
		
		));
		
		header('Location: '.SITE_URL.'index.php?app=home&ac=admin&mg=info&ts=list');
	
		break;
		
	//删除 
	case "delete":
	
		$infoid = intval($_GET['infoid']);
		$new['home']->delete('home_info',array(
			'infoid'=>$infoid,
		));
		
		qiMsg('删除成功！');
		
		break;
	
}