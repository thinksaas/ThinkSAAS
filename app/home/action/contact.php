<?php 
defined('IN_TS') or die('Access Denied.');
$strInfo = $db->once_fetch_assoc("select * from ".dbprefix."home_info where `infokey`='$ac'");
$title = '联系我们';
include template('contact');