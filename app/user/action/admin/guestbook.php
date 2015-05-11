<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "list":
	
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=user&ac=admin&mg=guestbook&ts=list&page=';
		$lstart = $page*20-20;
		
		$arrGuestbook	= $new['user']->findAll('user_gb',null,'addtime desc',null,$lstart.',20');
		
		$guestNum = $new['user']->findCount('user_gb');
		
		$pageUrl = pagination($guestNum, 20, $page, $url);
		
		include template('admin/guestbook_list');
	
		break;
		
	case "delete":
	
		$guestid = intval($_GET['guestid']);
		$page = intval($_GET['page']);
		
		$new['user']->delete('user_gb',array(
			'id'=>$guestid,
		));
	
		header('Location: '.SITE_URL.'index.php?app=user&ac=admin&mg=guestbook&ts=list&page='.$page);
	
		break;

}