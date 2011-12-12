<?php
defined('IN_TS') or die('Access Denied.');
//登录
switch($ts){
	case "":
		
		$title = '登录后台';
		include template("login");
		break;
		
	case "do":
		
		$email = trim($_POST['email']);
		$pwd = trim($_POST['pwd']);
		$cktime = $_POST['cktime'];
		
		if($email=='' || $pwd=='') qiMsg("所有输入项都不能为空^_^");
		
		if(valid_email($email) == false) qiMsg("Email书写不正确^_^");
		
		$countAdmin	= $db->once_fetch_assoc("select count(userid) from ".dbprefix."user where email='$email' and pwd='".md5($pwd)."'");
		
		if($countAdmin['count(userid)'] == 0) qiMsg("Email或者密码错误！");
		
		$strAdmin = $db->once_fetch_assoc("select userid,username,isadmin from ".dbprefix."user_info where email='$email'");
		
		if($strAdmin['isadmin'] != 1) qiMsg("我们是香港皇家警察，现在正式逮捕你！");
		
		$_SESSION['tsadmin']	= $strAdmin;
		
		header("Location: index.php?app=system");
		
		break;
		
	//退出 
	case "out":
		unset($_SESSION['tsadmin']);
		
		header("Location: index.php?app=system&ac=login");
		
		break;
}