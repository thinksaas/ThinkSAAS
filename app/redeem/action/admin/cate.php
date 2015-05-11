<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "list":
	
		$arrCate = $new['redeem']->findAll('redeem_cate');
	
		include template('admin/cate_list');
		break;
		
	case "add":
	
		include template('admin/cate_add');
		break;
		
	case "adddo":
	
		$catename  = t($_POST['catename']);
		$new['redeem']->create('redeem_cate',array(
			'catename'=>$catename,
		));
		
		header('Location: '.SITE_URL.'index.php?app=redeem&ac=admin&mg=cate&ts=list');
	
		break;
		
	case "edit":
	
		$cateid = intval($_GET['cateid']);
		
		$strCate = $new['redeem']->find('redeem_cate',array(
			'cateid'=>$cateid,
		));
	
		include template('admin/cate_edit');
		break;
		
	case "editdo":
		$cateid = intval($_POST['cateid']);
		$catename = t($_POST['catename']);
	
		$new['redeem']->update('redeem_cate',array(
			'cateid'=>$cateid,
		),array(
			'catename'=>$catename,
		));
	
		header('Location: '.SITE_URL.'index.php?app=redeem&ac=admin&mg=cate&ts=list');
		break;
		
	case "delete":
	
		$cateid = intval($_GET['cateid']);
		
		$new['redeem']->delete('redeem_cate',array(
			'cateid'=>$cateid,
		));
		
		$new['redeem']->update('redeem_goods',array(
			'cateid'=>$cateid,
		),array(
			'cateid'=>0,
		));
		
		qiMsg('删除成功！');
		
		break;
}