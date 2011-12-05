<?php
defined('IN_TS') or die('Access Denied.');
/* 
* 发送盒子
*/

$userid= $_GET['userid'];

$strTouser = $db->once_fetch_assoc("select * from ".dbprefix."user_info where userid='$userid'");

$title = '发送盒子';

include template("sendbox");