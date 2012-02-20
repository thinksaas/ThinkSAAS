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
	
		if($TS_USER['user'] != '') header("Location: ".SITE_URL);
		
		$jump = trim($_POST['jump']);
		
		$email = trim($_POST['email']);
		
		$pwd = trim($_POST['pwd']);
		
		$cktime = $_POST['cktime'];
		
		if($email=='' || $pwd=='') tsNotice('Email和密码都不能为空！');
		
		$isEmail = $new['user']->findCount('user',array(
			'email'=>$email,
		));
		
		if($isEmail == 0) tsNotice('Email不存在，你可能还没有注册！');
		
		$strUser = $new['user']->find('user',array(
			'email'=>$email,
		));
			
		if(md5($strUser['salt'].$pwd)!==$strUser['pwd']) tsNotice('密码错误！');	
		
		//用户信息
		$userData = $new['user']->find('user_info',array(
			'email'=>$email,
		));
		
		//记住登录Cookie
		 if($cktime != ''){   
			 setcookie("ts_email", $email, time()+$cktime,'/');   
			 setcookie("ts_pwd", $pwd, time()+$cktime,'/');
		 }    
			
		//用户session信息
		$sessionData = array(
			'userid' => $userData['userid'],
			'username'	=> $userData['username'],
			'areaid'	=> $userData['areaid'],
			'path'	=> $userData['path'],
			'face'	=> $userData['face'],
			'count_score'	=> $userData['count_score'],
			'isadmin'	=> $userData['isadmin'],
			'uptime'	=> $userData['uptime'],
		);
		$_SESSION['tsuser']	= $sessionData;
		
		//用户userid
		$userid = $userData['userid'];
		
		//积分记录
		$new['user']->create('user_scores',array(
			'userid'=>$userid,
			'scorename'=>'登录',
			'score'=>'10',
			'addtime'=>time(),
		));
		
		$strScore = $new['user']->find('user_scores',array(
			'userid'=>$userid,
		),'sum(score) score');
		
		//更新登录时间
		$new['user']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'uptime'=>time(),
			'count_score'=>$strScore['score'],
		));

		//跳转
		if($jump != ''){
			header("Location: ".$jump);
		}else{
			header('Location: '.SITE_URL);
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