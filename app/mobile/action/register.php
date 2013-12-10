<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "":
	
		include template('register');
		break;
		
		
	case "do":
	
	
		if($_POST['token'] != $_SESSION['token']) {
			$new['mobile']->mNotice('非法操作！');
		}
	
		$email		= trim($_POST['email']);
		$pwd			= trim($_POST['pwd']);
		$username		= t($_POST['username']);
		
		$authcode = strtolower($_POST['authcode']);
		
		
		/*禁止以下IP用户登陆或注册*/
		$arrIp = aac('system')->antiIp();
		if(in_array(getIp(),$arrIp)){
			header('Location: '.SITE_URL);
			exit;
		}

		$isEmail = $new['mobile']->findCount('user',array(
			'email'=>$email,
		));
		
		$isUserName = $new['mobile']->findCount('user_info',array(
			'username'=>$username,
		));
		
		if($email=='' || $pwd=='' || $username==''){
		
			$new['mobile']->mNotice('所有必选项都不能为空！');
		
		}
		
		if(valid_email($email) == false){
		
			$new['mobile']->mNotice('Email邮箱输入有误!');
			
		}
		
		if($isEmail > 0){
			$new['mobile']->mNotice('Email已经注册^_^');
		}
		
		
		if(count_string_len($username) < 4 || count_string_len($username) > 20){
			$new['mobile']->mNotice('姓名长度必须在4和20之间!');
		}
		
		if($isUserName > 0){
			$new['mobile']->mNotice("用户名已经存在，请换个用户名！");
		}
		
		if($TS_SITE['base']['isauthcode']){
			if($authcode != $_SESSION['verify']){
				$new['mobile']->mNotice("验证码输入有误，请重新输入！");
			}
		}
		
		$salt = md5(rand());
		
		$userid = $new['mobile']->create('user',array(
			'pwd'=>md5($salt.$pwd),
			'salt'=>$salt,
			'email'=>$email,
		));
		
		//插入用户信息			
		$new['mobile']->create('user_info',array(
			'userid'			=> $userid,
			'username' 	=> $username,
			'email'		=> $email,
			'ip'			=> getIp(),
			'addtime'	=> time(),
			'uptime'	=> time(),
		));
		

		//用户信息
		$userData = $new['mobile']->find('user_info',array(
			'userid'=>$userid,
		),'userid,username,path,face,isadmin,uptime');
		
		//用户session信息
		$_SESSION['tsuser']	= $userData;
		
		//对积分进行处理
		aac('user')->doScore($app,$ac,$ts);
		
		//跳转
		header('Location: '.tsUrl('mobile'));
	
	
		break;

}