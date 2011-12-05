<?php
defined('IN_TS') or die('Access Denied.');
 
if($TS_USER['user']['userid'] == '') header("Location: ".SITE_URL."index.php");

switch($ts){
	//发送消息页面
	case "message_add":
		
		$userid = intval($TS_USER['user']['userid']);
		$touserid = intval($_GET['touserid']);
		
		if($userid == $touserid || !$touserid) qiMsg("Sorry！自己不能给自己发送消息的！& 对方为空!");
		
		$strUser = $new['user']->getUserForApp($userid);
		
		$strTouser = $new['user']->getUserForApp($touserid);

		if(!$strTouser) qiMsg("Sorry！对方不存在!");
		$title = "发送短消息";
		include template("message_add");
		break;
	
	//
	case "message_add_do":
	
		$msg_userid = $_POST['userid'];
		$msg_touserid = $_POST['touserid'];
		$msg_content = $_POST['content'];
		aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		header("Location: ".SITE_URL."index.php?app=message&ac=my");
		
		break;
}