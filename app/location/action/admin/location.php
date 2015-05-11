<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "list":
	
		$arrLocation = $new['location']->findAll('location',null,'orderid asc');
		include template('admin/location_list');
		break;
		
	case "add":
		
		
		include template('admin/location_add');
		break;
		
	case "adddo":
		
		$title = t($_POST['title']);
		$content = trim($_POST['content']);
		
		$orderid = intval($_POST['orderid']);
		
		$locationid = $new['location']->create('location',array(
			'title'=>$title,
			'content'=>$content,
			'orderid'=>$orderid,
		));
		
		$arrUpload = tsUpload ( $_FILES ['photo'], $locationid, 'location', array ('jpg','gif','png','jpeg' ) );
		if ($arrUpload) {
			$new ['location']->update ( 'location', array (
					'locationid' => $locationid 
			), array (
					'path' => $arrUpload ['path'],
					'photo' => $arrUpload ['url'] 
			) );
		}
		
		header('Location: '.SITE_URL.'index.php?app=location&ac=admin&mg=location&ts=list');
		
		break;
		
	case "edit":
		
		$locationid = intval($_GET['locationid']);
		
		$strLocation = $new['location']->find('location',array(
			'locationid'=>$locationid,
		));
		
		include template('admin/location_edit');
		break;
		
	case "editdo":
		$locationid = intval($_POST['locationid']);
		$title = t($_POST['title']);
		$content = trim($_POST['content']);
		
		$orderid = intval($_POST['orderid']);
		
		$new['location']->update('location',array(
			'locationid'=>$locationid,
		),array(
			'title'=>$title,
			'content'=>$content,
			'orderid'=>$orderid,
		));
		
		$arrUpload = tsUpload ( $_FILES ['photo'], $locationid, 'location', array ('jpg','gif','png','jpeg' ) );
		if ($arrUpload) {
			$new ['location']->update ( 'location', array (
					'locationid' => $locationid 
			), array (
					'path' => $arrUpload ['path'],
					'photo' => $arrUpload ['url'] 
			) );
		}
		
		header('Location: '.SITE_URL.'index.php?app=location&ac=admin&mg=location&ts=list');
		
		break;
		
	case "delete":
	
		$locationid = intval($_GET['locationid']);
		$strLocation = $new['location']->find('location',array(
			'locationid'=>$locationid,
		));
		
		unlink('uploadfile/location/'.$strLocation['photo']);
		
		$new['location']->delete('location',array(
			'locationid'=>$locationid,
		));
		
		$new['location']->update('article',array(
			'locationid'=>$locationid,
		),array(
			'locationid'=>0,
		));
		
		$new['location']->update('attach',array(
			'locationid'=>$locationid,
		),array(
			'locationid'=>0,
		));
		
		$new['location']->update('group_topic',array(
			'locationid'=>$locationid,
		),array(
			'locationid'=>0,
		));
		
		$new['location']->update('photo',array(
			'locationid'=>$locationid,
		),array(
			'locationid'=>0,
		));
		
		$new['location']->update('user_info',array(
			'locationid'=>$locationid,
		),array(
			'locationid'=>0,
		));
		
		$new['location']->update('weibo',array(
			'locationid'=>$locationid,
		),array(
			'locationid'=>0,
		));
		
		qiMsg('操作成功！');
	
		break;
}