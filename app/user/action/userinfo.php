<?php 

$userid = intval($_GET['id']);

if($new['user']->isUser($userid)==false){

	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$title = '404';
	include pubTemplate("404");
	exit;

}

$strUser = $new['user']->getOneUser($userid);
$strUser['rolename'] = aac('user')->getRole($strUser['count_score']);

//是否关注
if($TS_USER['user']['userid'] != '' && $TS_USER['user']['userid'] != $strUser['userid']){
	$followNum = $db->once_num_rows("select * from ".dbprefix."user_follow where userid='".$TS_USER['user']['userid']."' and userid_follow='$userid'");
	if($followNum > '0'){
		$strUser['isfollow'] = true;
	}else{
		$strUser['isfollow'] = false;
	}
}else{
	$strUser['isfollow'] = false;
}

//他关注的用户
$followUsers = $db->fetch_all_assoc("select userid_follow from ".dbprefix."user_follow where userid='$userid' order by addtime desc limit 12");

if(is_array($followUsers)){
	foreach($followUsers as $item){
		$arrFollowUser[] =  $new['user']->getOneUser($item['userid_follow']);
	}
}

//加入的小组
$arrGroups = $db->fetch_all_assoc("select * from ".dbprefix."group_user where userid='$userid' limit 12");

if(is_array($arrGroups)){
	foreach($arrGroups as $key=>$item){
		$arrGroup[] = aac('group')->getOneGroup($item['groupid']);
	}
}