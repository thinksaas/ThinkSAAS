<?php
defined('IN_TS') or die('Access Denied.');
//重设密码

$title = '重设密码';


switch($ts){
	case "":
	
		$email = trim($_GET['mail']);
		$resetpwd = trim($_GET['set']);
		
		$userNum = $db->findCount('user',array(
			'email'=>$email,
			'resetpwd'=>$resetpwd,
		));
		
		if(empty($email) || empty($resetpwd)){
			tsNotice("迷路了吗？");
		}elseif(valid_email($email) == false){
			tsNotice("火星来的吗？");
		}elseif($userNum == '0'){
			tsNotice("好像地球不适合你哦^_^");
		}else{

			include template("resetpwd");

			
		}
		
		break;
	

	case "do":
	
		$email 	= trim($_POST['email']);
		$pwd 	= trim($_POST['pwd']);
		$repwd	= trim($_POST['repwd']);
		
		$resetpwd = trim($_POST['resetpwd']);

		$userNum = $db->findCount('user',array(
			'email'=>$email,
			'resetpwd'=>$resetpwd,
		));
		
		if(empty($email) || empty($pwd) || empty($repwd) || empty($resetpwd)){
			tsNotice("迷路了吗？");
		}elseif($userNum == '0'){
			tsNotice("好像地球不适合你哦^_^");
		}else{

			$db->update('user',array(
				'pwd'=>md5($pwd),
			),array(
				'email'=>$email,
			));
			
			tsNotice("密码修改成功^_^","点击登陆","index.php?app=user&ac=login");
		}
	
		
		break;
		
}
