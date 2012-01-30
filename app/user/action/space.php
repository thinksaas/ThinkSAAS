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


$title = $strUser['username'].'的个人空间';
include template("space");
