<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

	//列表 
	case "list":
	
		$arrInfo = $new['home']->findAll('home_info',null,'orderid asc');
	
		include template('admin/info_list');
		break;
	
	//添加 
	case "add":
		
		include template('admin/info_add');
		break;
		
	case "adddo":

		$title = trim($_POST['title']);
		$content = tsClean($_POST['content']);

		$orderid = intval($_POST['orderid']);
		
		$new['home']->create('home_info',array(

			'title'=>$title,
			'content'=>$content,
            'orderid'=>$orderid,
		
		));
		
		header('Location: '.SITE_URL.'index.php?app=home&ac=admin&mg=info&ts=list');
	
		break;
		
	//编辑 
	case "edit":
	
		$infoid = intval($_GET['infoid']);

		$strInfo = $new['home']->find('home_info',array(
			'infoid'=>$infoid,
		));
		
		$strInfo['content'] = tsDecode($strInfo['content']);
	
	
		include template('admin/info_edit');
	
		break;
		
	case "editdo":
	
		$infoid = intval($_POST['infoid']);

		$title = trim($_POST['title']);

		$content = tsClean($_POST['content']);

        $orderid = intval($_POST['orderid']);
		
		$new['home']->update('home_info',array(
		
			'infoid'=>$infoid,
		
		),array(

			'title'=>$title,
			'content'=>$content,
            'orderid'=>$orderid,
		
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