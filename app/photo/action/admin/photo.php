<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "list":
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		
		$lstart = $page*10-10;
		
		$url = SITE_URL.'index.php?app=photo&ac=admin&mg=photo&ts=list&page=';
		
		$arrPhoto = $db->fetch_all_assoc("select * from ".dbprefix."photo order by addtime desc limit $lstart,10");
		
		$photoNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."photo");
		
		$pageUrl = pagination($photoNum['count(*)'], 10, $page, $url);
		
		include template("admin/photo_list");
		break;
		
	//推荐图片 
	case "isrecommend":
	
		$photoid = intval($_GET['photoid']);
		
		$strPhoto = $db->once_fetch_assoc("select isrecommend from ".dbprefix."photo where `photoid`='$photoid'");
		
		if($strPhoto['isrecommend']==0){
			$db->query("update ".dbprefix."photo set `isrecommend`='1' where `photoid`='$photoid'");
		}else{
			$db->query("update ".dbprefix."photo set `isrecommend`='0' where `photoid`='$photoid'");
		}
	
		qiMsg("操作成功！");
	
		break;
	
}