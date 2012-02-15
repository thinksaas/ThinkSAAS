<?php 
defined('IN_TS') or die('Access Denied.');
$strInfo = $db->find("select * from ".dbprefix."home_info where `infokey`='$ac'");
$title = '用户协议';
include template('agreement');