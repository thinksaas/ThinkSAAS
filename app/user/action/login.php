<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "":
		if(intval($TS_USER['user']['userid']) > 0) tsNotice("已经登陆啦!");
		
		$jump = $_SERVER['HTTP_REFERER'];

		$title = L::login_login;
		include template("login");
		break;
	
	
	case "do":
		if($TS_USER['user'] != '') header("Location: ".SITE_URL."index.php");
		
		$jump = trim($_POST['jump']);
		
		$email = trim($_POST['email']);
		$pwd = md5(trim($_POST['pwd']));
		
		$cktime = $_POST['cktime'];
	
		$isUser	= $db->once_fetch_assoc("select count(*) from ".dbprefix."user where email='$email' and pwd='$pwd'");
		
		$isEmail = $db->once_num_rows("select count(*) from ".dbprefix."user where email='$email'");
		
		if($email=='' || $pwd==''){

			tsNotice(L::login_entryempty);
			
		}elseif($isEmail['count(*)'] == '0'){

			tsNotice(L::login_emailnotregistered);
			
		}elseif($isEmail['count(*)'] > 0 && $isUser['count(*)'] == 0){
			
			tsNotice(L::login_pwderror);
			
		}else{
		
			$userData	= $db->once_fetch_assoc("select  * from ".dbprefix."user_info where email='$email'");
			
			//记住登录Cookie
			 if($cktime != ''){   
				 setcookie("ts_email", $email, time()+$cktime,'/');   
				 setcookie("ts_pwd", $pwd, time()+$cktime,'/');
			 }   
			
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
			
			//用户userid
			$userid = $userData['userid'];
			
			
			//更新登录时间
			$db->query("update ".dbprefix."user_info set `uptime`='".time()."' where userid='$userid'");

			//跳转
			if($jump != ''){
				header("Location: ".$jump);
			}else{
				header('Location: '.SITE_URL);
			}
		
		}
		
		break;
	
	//退出	
	case "out":
		
		$jump = $_SERVER['HTTP_REFERER'];
		
		session_destroy();
		setcookie("ts_email", '', time()+3600,'/');   
		setcookie("ts_pwd", '', time()+3600,'/');
		
		if($jump != ''){
			header('Location: '.$jump);
		}else{
			header('Location: '.SITE_URL);
		}
		
		break;
}