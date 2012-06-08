<?php
defined('IN_TS') or die('Access Denied.');
// 用户是否登录
$userid = aac('user') -> isLogin();

switch ($ts) {
	// 创建小组
	   case "post":
	       $gid=intval($_POST['gid']);
		   include THINKROOT.'/app/group/thread/'.$_POST['v'].'/code.php';
         
		break;
		case $ts:
	       $gid=intval($_POST['gid']);
		   include THINKROOT.'/app/group/thread/'.$ts.'/action.php';
           
		break;
} 
