<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "list":
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=message&ac=admin&mg=msg&ts=list&page=';
		$lstart = $page*20-20;
		$arrMsg = $new['message']->findAll('message',null,'addtime desc',null,$lstart.',20');
		
		$msgNum = $new['message']->findCount('message');
		$pageUrl = pagination($msgNum, 20, $page, $url);
		
		include template('admin/msg_list');
		break;
		
	//删除 
	case "delete":
	
		$messageid = intval($_GET['messageid']);
		$page = intval($_GET['page']);
		
		$new['message']->delete('message',array(
			'messageid'=>$messageid,
		));
		
		header('Location: '.SITE_URL.'index.php?app=message&ac=admin&mg=msg&ts=list&page='.$page);
	
		break;
	

}