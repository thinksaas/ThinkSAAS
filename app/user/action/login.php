<?php
defined('IN_TS') or die('Access Denied.');
//程序主体
switch($ts){
	case "":
		if(intval($TS_USER['user']['userid']) > 0) tsNotice("已经登陆啦!");
		
		//记录上次访问地址
		$jump = $_SERVER['HTTP_REFERER'];

		$title = '登录';
		include template("login");
		break;
	
	//执行登录
	case "do":
		if($TS_USER['user'] != '') header("Location: ".SITE_URL."index.php");
		
		$jump = trim($_POST['jump']);
		
		$email = trim($_POST['email']);
		$pwd = md5(trim($_POST['pwd']));
		
		$authcode = strtoupper($_POST['authcode']); //strtoupper将字符转成大写
		
		$cktime = $_POST['cktime'];
	
		$isUser	= $db->once_fetch_assoc("select count(*) from ".dbprefix."user where email='$email' and pwd='$pwd'");
		
		$isEmail = $db->once_num_rows("select count(*) from ".dbprefix."user where email='$email'");
		
		if($email=='' || $pwd==''){

			tsNotice("所有输入项都不能为空^_^");
			
		}elseif(valid_email($email) == false){
			
			tsNotice("Email书写不正确^_^");
			
		}elseif($isEmail['count(*)'] == '0'){

			tsNotice("你还没有注册呢，请注册吧^_^");
			
		}elseif($isEmail['count(*)'] > 0 && $isUser['count(*)'] == 0){
			
			tsNotice("密码输入有误，忘记可以找回密码^_^");
			
		}else{
		
			$userData	= $db->once_fetch_assoc("select  * from ".dbprefix."user_info where email='$email'");
			
			if($userData['isenable'] == 1) tsNotice("sorry，你的帐号已被禁用！");
			
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