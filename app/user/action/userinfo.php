<?php 
defined('IN_TS') or die('Access Denied.');
$userid = tsIntval($_GET['id']);

if($new['user']->isUser($userid)==false){

	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$title = '404';
	include pubTemplate("404");
	exit;

}

$strUser = $new['user']->getOneUser($userid);

//是否关注
if($TS_USER['userid'] != '' && $TS_USER['userid'] != $strUser['userid']){
	$followNum = $db->once_num_rows("select * from ".dbprefix."user_follow where userid='".$TS_USER['userid']."' and touserid='$userid'");
	if($followNum > '0'){
		$strUser['isfollow'] = true;
	}else{
		$strUser['isfollow'] = false;
	}
}else{
	$strUser['isfollow'] = false;
}

//他关注的用户
$followUsers = $db->fetch_all_assoc("select touserid from ".dbprefix."user_follow where userid='$userid' order by addtime desc limit 12");

if(is_array($followUsers)){
	foreach($followUsers as $item){
		$arrFollowUser[] =  $new['user']->getSimpleUser($item['touserid']);
	}
}