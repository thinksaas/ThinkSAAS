<?php
defined('IN_TS') or die('Access Denied.');

//我的首页
$userid = intval($TS_USER['user']['userid']);

if($userid == '0') header("Location: ".SITE_URL."index.php");

$strUser = $new['user']->getOneUserByUserid($userid);

$title = '我的首页';

include template("my");