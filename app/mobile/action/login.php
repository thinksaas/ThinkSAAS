<?php
defined('IN_TS') or die('Access Denied.');



switch($ts){
	
	case "":
	
	
		include template('login');
		break;
		
		
	case "do":
	
	
		if($TS_USER['user'] != '') header("Location: ".SITE_URL);
		
		if($_POST['token'] != $_SESSION['token']) {
			$new['mobile']->mNotice('非法操作！');
		}
		
		/*禁止以下IP用户登陆或注册*/
		$arrIp = aac('system')->antiIp();
		if(in_array(getIp(),$arrIp)){
			header('Location: '.SITE_URL);
			exit;
		}
		
		$jump = trim($_POST['jump']);
		
		$email = trim($_POST['email']);
		
		$pwd = trim($_POST['pwd']);
		
		$cktime = $_POST['cktime'];
		
		if($email=='' || $pwd=='') {
		
			$new['mobile']->mNotice('Email和密码都不能为空！');
		
		}
		
		$isEmail = $new['mobile']->findCount('user',array(
			'email'=>$email,
		));
		
		$strUser = $new['mobile']->find('user',array(
			'email'=>$email,
		));

		//此处预留其他登录接口
		
		if($isEmail == 0) $new['mobile']->mNotice('Email不存在，你可能还没有注册！');
		
			
		if(md5($strUser['salt'].$pwd)!==$strUser['pwd']) $new['mobile']->mNotice('密码错误！');	
		
		//更新登录时间，用作自动登录
		$new['mobile']->update('user_info',array(
			'email'=>$email,
		),array(
			'ip'=>getIp(),  //更新登录ip
			'uptime'=>time(),   //更新登录时间
		));
		
		//用户信息
		$userData = $new['mobile']->find('user_info',array(
			'email'=>$email,
		));
		
		//记住登录Cookie，根据用户Email和最后登录时间
		 if($cktime != ''){   
			 setcookie("ts_email", $userData['email'], time()+$cktime,'/');   
			 setcookie("ts_uptime", $userData['uptime'], time()+$cktime,'/');
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

		//跳转
		if($jump != ''){
			header("Location: ".$jump);
		}else{
			header('Location: '.tsUrl('mobile'));
		}
	
	
		break;
		
		
	case "out":
	
		aac('user')->logout();
		header('Location: '.tsUrl('mobile','login'));
		exit;
		break;

}