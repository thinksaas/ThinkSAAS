<?php
defined('IN_TS') or die('Access Denied.');
 
//用户是否登录
$userid = aac('user')->isLogin();

switch($ts){
	//发送消息页面
	case "add":
		
		$touserid = intval($_GET['touserid']);
		
		if($userid == $touserid || !$touserid) tsNotice("Sorry！自己不能给自己发送消息的！& 对方为空!");
		
		$strUser = $new['user']->getOneUser($userid);
		
		$strTouser = $new['user']->getOneUser($touserid);

		if(!$strTouser) tsNotice("Sorry！对方不存在!");
		$title = "发送短消息";
		include template("message_add");
		break;
	
	case "do":
	
		$msg_userid = $userid;
		$msg_touserid = intval($_POST['touserid']);
		$msg_content = tsFilter($_POST['content']);
		
		aac('system')->antiWord($msg_content);
		
		aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		header("Location: ".tsUrl('message','my'));
		
		break;
}