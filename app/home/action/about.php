<?php 
defined('IN_TS') or die('Access Denied.');
$strInfo = $db->find("select * from ".dbprefix."home_info where `infokey`='$ac'");

$title = '关于我们';
include template('about');