<?php 
defined('IN_TS') or die('Access Denied.');
$attachid = $_GET['attachid'];

$strAttach = $db->find("select * from ".dbprefix."attach where attachid='$attachid'");
$strAttach['user'] = aac('user')->getOneUser($strAttach['userid']);

$userid = $strAttach['userid'];
//用户的附件 
$userAttach = $db->findAll("select * from ".dbprefix."attach where userid='$userid'");

$title = $strAttach['attachname'];
include template("show");