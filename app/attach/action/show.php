<?php 
defined('IN_TS') or die('Access Denied.');
$attachid = $_GET['attachid'];

$strAttach = $db->once_fetch_assoc("select * from ".dbprefix."attach where attachid='$attachid'");
$strAttach['user'] = aac('user')->getSimpleUser($strAttach['userid']);

$userid = $strAttach['userid'];
//用户的附件 
$userAttach = $db->fetch_all_assoc("select * from ".dbprefix."attach where userid='$userid'");

$title = $strAttach['attachname'];
include template("show");