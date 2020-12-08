<?php
defined('IN_TS') or die('Access Denied.');
/* 
* 发送盒子
*/

$userid= tsIntval($_GET['userid']);

$strTouser = aac('user')->getSimpleUser($userid);

$title = '发送盒子';

include template("sendbox");