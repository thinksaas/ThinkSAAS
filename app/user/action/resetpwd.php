<?php
defined('IN_TS') or die('Access Denied.');
//重设密码

$title = '重设密码';


switch($ts){
	case "":
	
		$email = trim($_GET['mail']);
		$resetpwd = trim($_GET['set']);
		
		$userNum = $db->once_num_rows("select * from ".dbprefix."user where email='$email' and resetpwd='$resetpwd'");
		
		if(empty($email) || empty($resetpwd)){
			qiMsg("迷路了吗？");
		}elseif(valid_email($email) == false){
			qiMsg("火星来的吗？");
		}elseif($userNum == '0'){
			qiMsg("好像地球不适合你哦^_^");
		}else{

			include template("resetpwd");

			
		}
		
		break;
	

	case "do":
	
		$email 	= trim($_POST['email']);
		$pwd 	= trim($_POST['pwd']);
		$repwd	= trim($_POST['repwd']);
		
		$resetpwd = trim($_POST['resetpwd']);
		
		$userNum = $db->once_num_rows("select * from ".dbprefix."user where email='$email' and resetpwd='$resetpwd'");
		
		if(empty($email) || empty($pwd) || empty($repwd) || empty($resetpwd)){
			qiMsg("迷路了吗？");
		}elseif($userNum == '0'){
			qiMsg("好像地球不适合你哦^_^");
		}else{
			$db->query("update ".dbprefix."user set pwd='".md5($pwd)."' where email='$email'");
			
			qiMsg("密码修改成功^_^","点击登陆","index.php?app=user&ac=login");
		}
	
		
		break;
		
}
