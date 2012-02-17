<?php
defined('IN_TS') or die('Access Denied.');
//重设密码

$title = '重设密码';


switch($ts){
	case "":
	
		$email = trim($_GET['mail']);
		$resetpwd = trim($_GET['set']);
		
		$userNum = $new['user']->findCount('user',array(
			'email'=>$email,
			'resetpwd'=>$resetpwd,
		));
		
		if($email=='' || $resetpwd==''){
			tsNotice("你应该去火星生活啦！");
		}elseif($userNum == 0){
			tsNotice("你应该去火星生活啦！");
		}else{

			include template("resetpwd");

		}
		
		break;
	

	case "do":
	
		$email 	= trim($_POST['email']);
		$pwd 	= trim($_POST['pwd']);
		$repwd	= trim($_POST['repwd']);
		
		$resetpwd = trim($_POST['resetpwd']);
		
		$userNum = $new['user']->findCount('user',array(
			'email'=>$email,
			'resetpwd'=>$resetpwd,
		));
		
		if($email=='' || $pwd=='' || $repwd=='' || $resetpwd==''){
			tsNotice("所有输入项都不能为空！");
		}elseif($userNum == '0'){
			tsNotice("你应该去火星生活啦！");
		}else{
		
			$new['user']->update('user',array(
				'pwd'=>md5($salt.$pwd),
				'salt'=>$salt,
			),array(
				'email'=>$email,
			));
			
			tsNotice("密码修改成功^_^","点击登陆",SITE_URL.tsurl('user','login'));
		}
	
		
		break;
		
}
