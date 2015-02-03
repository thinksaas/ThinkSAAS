<?php
defined('IN_TS') or die('Access Denied.');
//重设密码


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

			$title = '重设密码';
			include template("resetpwd");

		}
		
		break;
	

	case "do":
	
		$js = intval($_GET['js']);
	
		$email 	= trim($_POST['email']);
		$pwd 	= trim($_POST['pwd']);
		$repwd	= trim($_POST['repwd']);
		
		$resetpwd = trim($_POST['resetpwd']);
		
		$userNum = $new['user']->findCount('user',array(
			'email'=>$email,
			'resetpwd'=>$resetpwd,
		));
		
		if($email=='' || $pwd=='' || $repwd=='' || $resetpwd==''){
			getJson("所有输入项都不能为空！",$js);
		}elseif($userNum == '0'){
			getJson("你应该去火星生活啦！",$js);
		}else{
		
			$salt = md5(rand());
			
			$new['user']->update('user',array(
				'email'=>$email,
			),array(
				'pwd'=>md5($salt.$pwd),
				'salt'=>$salt,
				'resetpwd'=>'',
			));
			
			
			getJson("密码修改成功^_^",$js);
		
		}
			
		break;
		
}
