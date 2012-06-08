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
			
			//Ucenter
			if($TS_SITE['base']['isucenter']==1){
				$user =$db -> once_fetch_assoc("select ucid from ts_user_info where email='$email'"); 
				$uc=uc_get_user($user['ucid'],1);
				$status=uc_user_edit($uc[1],'',$repwd,'',1);
			}
		
			$salt = md5(rand());
			
			$new['user']->update('user',array(
				'email'=>$email,
			),array(
				'pwd'=>md5($salt.$pwd),
				'salt'=>$salt,
			));
			
			if(isset($status) && $status!=1)   tsNotice("密码修改失败，请重试");
			
			tsNotice("密码修改成功^_^","点击登陆",SITE_URL.tsUrl('user','login'));
		
		}
			
		break;
		
}
