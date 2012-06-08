<?php
defined('IN_TS') or die('Access Denied.');

$userid = intval($_GET['userid']);

if($new['user']->isUser($userid)==false){

	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$title = '404';
	include pubTemplate("404");
	exit;

}

$strUser = $new['user']->getOneUser($userid);

if($strUser == '') header("Location: ".SITE_URL."index.php");

//跟随他的用户
$followedUsers = $db->fetch_all_assoc("select userid from ".dbprefix."user_follow where userid_follow='$userid' order by addtime");

if(is_array($followedUsers)){
	foreach($followedUsers as $item){
		$arrFollowedUser[] =  $new['user']->getOneUser($item['userid']);
	}
}

$title =  '谁在跟随'.$strUser['username'];
include template('followed');