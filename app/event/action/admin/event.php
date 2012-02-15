<?php 

switch($ts){
	case "list":
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = 'index.php?app=event&ac=admin&mg=event&ts=list&page=';
		$lstart = $page*10-10;
		$arrEvent = $db->findAll("select * from ".dbprefix."event order by addtime desc limit $lstart,10");
		$eventNum = $db->findCount('event');
		$pageUrl = pagination($eventNum, 10, $page, $url);

		include template("admin/event_list");
		
		break;
}