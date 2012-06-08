<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 用户被跟随
 */

$userid = intval($_GET['id']);

if($new['user']->isUser($userid)==false){

	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$title = '404';
	include pubTemplate("404");
	exit;

}

$strUser = $new['user']->getOneUser($userid);

if($strUser == '') header("Location: ".SITE_URL."index.php");

//他跟随的用户
$followUsers = $db->fetch_all_assoc("select userid_follow from ".dbprefix."user_follow where userid='$userid'");

if(is_array($followUsers)){
	foreach($followUsers as $item){
		$arrFollowUser[] =  $new['user']->getOneUser($item['userid_follow']);
	}
}

$title = $strUser['username'].'关注的人';
include template("follow");