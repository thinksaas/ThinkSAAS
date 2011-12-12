<?php 

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
	
}