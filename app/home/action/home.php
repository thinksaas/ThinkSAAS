<?php
defined('IN_TS') or die('Access Denied.');
$userid = intval($TS_USER['user']['userid']);

$arrUserId = $db->fetch_all_assoc("select userid_follow from ".dbprefix."user_follow where `userid`='$userid'");

foreach($arrUserId as $item){
	
}