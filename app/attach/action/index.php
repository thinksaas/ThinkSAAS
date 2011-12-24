<?php 
defined('IN_TS') or die('Access Denied.');
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$url = SITE_URL.tsurl('attach','index',array('page'=>''));
$lstart = $page*20-20;
$arrAttachs = $db->fetch_all_assoc("select * from ".dbprefix."attach order by addtime desc limit $lstart,20");
$attachNum = $db->once_num_rows("select * from ".dbprefix."attach");
$pageUrl = pagination($attachNum, 20, $page, $url);

foreach($arrAttachs as $key=>$item){
	$arrAttach[] = $item;
	$arrAttach[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
}

if($page > 0){
	$title = '资料 - 第'.$page.'页';
}else{
	$title = '资料';
}

include template("index");