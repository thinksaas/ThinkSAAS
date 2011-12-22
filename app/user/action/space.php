<?php
defined('IN_TS') or die('Access Denied.');

//用户空间

$userid = intval($_GET['userid']);

if($userid == 0){
	header("Location: ".SITE_URL);
	exit;
}

$new['user']->isUser($userid);

$strUser = $new['user']->getOneUserByUserid($userid);

//是否跟随
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

//他跟随的用户
$followUsers = $db->fetch_all_assoc("select userid_follow from ".dbprefix."user_follow where userid='$userid' order by addtime limit 12");

if(is_array($followUsers)){
	foreach($followUsers as $item){
		$arrFollowUser[] =  $new['user']->getOneUserByUserid($item['userid_follow']);
	}
}

//加入的小组
$arrGroups = $db->fetch_all_assoc("select * from ".dbprefix."group_users where userid='$userid' limit 12");

if(is_array($arrGroups)){
	foreach($arrGroups as $key=>$item){
		$arrGroup[] = aac('group')->getOneGroup($item['groupid']);
	}
}

//自己的帖子 
$arrMyTopic = $db->fetch_all_assoc("select * from ".dbprefix."group_topics where userid='$userid' order by addtime desc limit 15");

//回复的帖子 
$arrComments = $db->fetch_all_assoc("select topicid from ".dbprefix."group_topics_comments where userid='$userid' group by topicid order by addtime desc limit 15");
if(is_array($arrComments)){
	foreach($arrComments as $item){
		$oneTopic = $db->once_fetch_assoc("select * from ".dbprefix."group_topics where topicid='".$item['topicid']."'");
		if($oncTopic['userid'] != $userid){
			$arrMyComment[] = $oneTopic;
		}
		
	}

}
//收藏的帖子 
$arrMyCollect = $new['user']->getCollectTopic($userid,15);

$title = $strUser['username'].'的个人空间';
include template("space");
