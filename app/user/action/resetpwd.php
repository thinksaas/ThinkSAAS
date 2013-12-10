<?php
defined('IN_TS') or die('Access Denied.');
//重设密码


switch($ts){
	case "":
	
		$email = tsFilter($_GET['mail']);
		$resetpwd = tsFilter($_GET['set']);
		
		$userNum = $new['user']->findCount('user',array(
			'email'=>$email,
			'resetpwd'=>$resetpwd,
		));
		
		if($email=='' || $resetpwd==''){
			tsNotice("你应该去火星生活啦！");
		}elseif($userNum == 0){
			tsNotice("你应该去火星生活啦！");
		}else{

			$title = '重设密码';
			include template("resetpwd");

		}
		
		break;
	

	case "do":
	
		$email 	= trim($_POST['email']);
		$pwd 	= t($_POST['pwd']);
		$repwd	= t($_POST['repwd']);
		
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
		
			$salt = md5(rand());
			
			$new['user']->update('user',array(
				'email'=>$email,
			),array(
				'pwd'=>md5($salt.$pwd),
				'salt'=>$salt,
			));
			
			if(isset($status) && $status!=1)   tsNotice("密码修改失败，请重试");
			
			tsNotice("密码修改成功^_^","点击登陆",tsUrl('user','login'));
		
		}
			
		break;
		
}
