<?php
defined('IN_TS') or die('Access Denied.');

//设置用户信息

$userid = intval($TS_USER['user']['userid']);

if($userid == '0') header("Location: ".SITE_URL."index.php");

$strUser = $new['user']->getOneUserByUserid($userid);

if($userid != $strUser['userid']) header("Location: ".SITE_URL."index.php");


switch($ts){
	//修改基本信息
	case "base":
		$strArea = aac('location')->getAreaForApp($strUser['areaid']);
		$title = '基本设置';
		include template("set_base");
		break;
	
	case "base_do":
	
		$username = t($_POST['username']);
		
		$arrData = array(
			'username' => $username,
			'blog'		=> t($_POST['blog']),
			'about'	=> h($_POST['about']),
		);

		if($username == '') tsNotice('用户名不能为空！');
		if(strlen($username) < 4 || strlen($username) > 20) tsNotice("用户名长度必须在4到20字符之间!");
		
		if($username != $strUser['username']){
			$isUserName = $db->once_fetch_assoc("select count(*) from ".dbprefix."user_info where username='$username'");
			if($isUserName['count(*)'] > 0) tsNotice("用户名已经存在，请换个用户名！");
		}

		$db->updateArr($arrData,dbprefix."user_info","where userid='$userid'");

		tsNotice("基本资料更新成功！");
		break;
	
	//修改头像
	case "face":

		$title = '头像设置';
		include template("set_face");

		break;
	
	//修改密码
	case "pwd":
	
		$title = '密码修改';
		include template("set_pwd");

		break;
}