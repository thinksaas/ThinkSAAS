<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	//分类列表 
	case "list":
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=group&ac=admin&mg=album&ts=list&page=';
		$lstart = $page*10-10;
		
		$arrAlbum = $new['group']->findAll('group_album',null,'addtime desc',null,$lstart.',10');
		
		$albumNum = $new['group']->findCount('group_album');
		
		$pageUrl = pagination($albumNum, 10, $page, $url);

		include template("admin/album_list");
		
		break;
	
	//分类删除
	case "delete":
		
		$albumid = intval($_GET['albumid']);
		
		$new['group']->delete('group_album',array(
			'albumid'=>$albumid,
		));
		
		$new['group']->delete('group_album_topic',array(
			'albumid'=>$albumid,
		));
		
		qiMsg('删除成功！');
		
		break;
	
}