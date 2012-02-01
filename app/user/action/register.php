<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "":
		if(intval($TS_USER['user']['userid']) > 0) tsNotice(L::register_exitregister);
		
		//invite userid
		$fuserid = intval($_GET['fuserid']);
	
		$title = L::register_register;
		
		include template("register");
		break;

	case "do":
		$email		= trim($_POST['email']);
		$pwd			= trim($_POST['pwd']);
		$repwd		= trim($_POST['repwd']);
		$username		= t($_POST['username']);
		
		$fuserid = intval($_POST['fuserid']);
		
		$authcode = strtoupper($_POST['authcode']);

		$isEmail = $db->once_fetch_assoc("select count(*) from ".dbprefix."user where email='$email'");
		$isUserName = $db->once_fetch_assoc("select count(*) from ".dbprefix."user_info where username='$username'");
		
		
		if(empty($email) || empty($pwd) || empty($repwd) || empty($username)){
		
			tsNotice(L::register_entryempty);
			
		}elseif(valid_email($email) == false){
		
			tsNotice(L::register_emailerror);
		
		}elseif($isEmail['count(*)'] > 0){
		
			tsNotice(L::register_emialregistered);
			
		}elseif($pwd != $repwd){
		
			tsNotice(L::register_pwdnotsame);
			
		}elseif(strlen($username) < 4 || strlen($username) > 20){
		
			tsNotice(L::register_usernamelength);
		
		}elseif($isUserName['count(*)'] > 0){
		
			tsNotice(L::register_usernameregistered);
			
		}elseif($authcode != $_SESSION['authcode']){
		
			tsNotice(L::register_verificationerror);
			
		}else{
		
			$salt = md5(rand());
			
			$db->query("insert into ".dbprefix."user (`pwd` , `salt`,`email`) values ('".md5($salt.$pwd)."', '$salt' ,'$email');");
			
			$userid = $db->insert_id();
			
			//user info
			$arrData = array(
				'userid'			=> $userid,
				'fuserid'	=> $fuserid,
				'username' 	=> $username,
				'email'		=> $email,
				'addtime'	=> time(),
				'uptime'	=> time(),
			);
			
			//input user info
			$db->insertArr($arrData,dbprefix.'user_info');
			
			$userData = $db->once_fetch_assoc("select * from ".dbprefix."user_info where userid='$userid'");
			
			//user session
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