<?php
defined('IN_TS') or die('Access Denied.');
//用户注册
switch($ts){
	case "":
		if(intval($TS_USER['user']['userid']) > 0) tsNotice("请退出后再注册！");
		
		//邀请用户ID
		$fuserid = intval($_GET['fuserid']);
	
		$title = '注册';
		
		include template("register");
		break;

	case "do":
		$email		= trim($_POST['email']);
		$pwd			= trim($_POST['pwd']);
		$repwd		= trim($_POST['repwd']);
		$username		= t($_POST['username']);
		
		$fuserid = intval($_POST['fuserid']);
		
		$authcode = strtoupper($_POST['authcode']); //strtoupper将字符转成大写

		$isEmail = $db->once_num_rows("SELECT * FROM ".dbprefix."user WHERE email='$email'");
		$isusername = $db->once_num_rows("select * from ".dbprefix."user_info where username='$username'");
		
		
		if(empty($email) || empty($pwd) || empty($repwd) || empty($username)){
			tsNotice('所有必选项都不能为空！');
		}elseif(valid_email($email) == false){
			tsNotice('Email邮箱输入有误!');
		}elseif($isEmail != '0'){
			tsNotice('Email已经注册^_^');
		}elseif($pwd != $repwd){
			tsNotice('两次输入密码不正确！');
		}elseif(strlen($username) < 4 || strlen($username) > 20){
			tsNotice('姓名长度必须在4和20之间!');
		}elseif($isusername > 0){
			tsNotice("用户名已经存在，请换个用户名！");
		}elseif($authcode != $_SESSION['authcode']){
			tsNotice("验证码输入有误，请重新输入！");
		}else{
			
			$db->query("INSERT INTO ".dbprefix."user (`pwd` , `email`) VALUES ('".md5($pwd)."', '$email');");
			
			$userid = $db->insert_id();
			
			//用户信息
			$arrData = array(
				'userid'			=> $userid,
				'fuserid'	=> $fuserid,
				'username' 	=> $username,
				'email'		=> $email,
				'addtime'	=> time(),
				'uptime'	=> time(),
			);
			
			//插入用户信息
			$db->insertArr($arrData,dbprefix.'user_info');
			
			//用户信息
			$userData = $db->once_fetch_assoc("select * from ".dbprefix."user_info where userid='$userid'");
			
			//用户session信息
			$sessionData = array(
				'userid' => $userData['userid'],
				'username'	=> $userData['username'],
				'path'	=> $userData['path'],
				'face'	=> $userData['face'],
				'isadmin'	=> $userData['isadmin'],
				'uptime'	=> $userData['uptime'],
			);
			
			$_SESSION['tsuser']	= $sessionData;
			
			//发送系统消息(恭喜注册成功)
			$msg_userid = '0';
			$msg_touserid = $userid;
			$msg_content = '亲爱的 '.$username.' ：<br />您成功加入了 '
										.$TS_SITE['base']['site_title'].'<br />在遵守本站的规定的同时，享受您的愉快之旅吧!';
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
			
			//跳转
			header('Location: '.SITE_URL.'index.php');
			
		}
	break;
}