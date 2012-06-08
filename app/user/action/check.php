<?php 
defined('IN_TS') or die('Access Denied.');
switch($ts){

	//验证Email是否唯一
	case "inemail":
	
		$email = $_GET['email'];
	
		$emailNum = $new['user']->findCount('user',array(
			'email'=>$email,
		));
		
		if($emailNum > '0'):
			echo 'false';
		else:
			echo 'true';
		endif;
		
		break;
	
	//验证用户名是否唯一
	case "isusername":
		$username = $_GET['username'];
		$usernameNum = $db->once_num_rows("select * from ".dbprefix."user_info where `username`='".$username."'");
		
		if($usernameNum > '0'):
			echo 'false';
		else:
			echo 'true';
		endif;
		break;
		
	//验证邀请码是否使用
	case "isinvitecode":
		
		$invitecode = trim($_GET['invitecode']);
		
		$codeNum = $db->once_num_rows("select * from ".dbprefix."user_invites where invitecode='$invitecode' and isused='0'");
		
		if($codeNum > 0){
			echo 'true';
		}else{
			echo 'false';
		}
		
		break;

}