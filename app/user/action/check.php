<?php 
defined('IN_TS') or die('Access Denied.');
switch($ts){

	//验证Email是否唯一
	case "inemail":
	
		//$email = $_GET['email'];
		$email = tsFilter($_POST["param"]);
	
		$emailNum = $new['user']->findCount('user',array(
			'email'=>$email,
		));
		
		$isAntiEmail = $new['user']->findCount('anti_email',array(
			'email'=>$email,
		));
		
		if($emailNum > 0 || $isAntiEmail >0){
			echo '{"info":"Email已经存在","status":"n"}';
		}else{
			echo '{"info":"通过！","status":"y"}';
		}
		
		break;
	
	//验证用户名是否唯一
	case "isusername":
		$username = tsFilter($_POST["param"]);
		
		
		if($TS_APP['banuser']){
		
			$arrUserName = explode('|',$TS_APP['banuser']);
			if(in_array($username,$arrUserName)){
				echo '{"info":"用户名已经存在！","status":"n"}';
				exit;
			}
		
		}
		
		
		$usernameNum = $new['user']->findCount('user_info',array(
			'username'=>$username,
		));
		
		
		
		if($usernameNum > '0'){
			echo '{"info":"用户名已经存在！","status":"n"}';
		}else{
			echo '{"info":"验证成功！","status":"y"}';
		}

		break;
		
	//验证邀请码是否使用
	case "isinvitecode":
		
		$invitecode = tsFilter($_GET['invitecode']);
		
		$codeNum = $db->once_num_rows("select * from ".dbprefix."user_invites where invitecode='$invitecode' and isused='0'");
		
		if($codeNum > 0){
			echo 'true';
		}else{
			echo 'false';
		}
		
		break;
		
	//验证码
	case "code":
		$authcode = strtolower(tsFilter($_POST["param"]));
		
		if($authcode == $_SESSION['verify']){
			echo '{"info":"通过！","status":"y"}';
		}else{
			echo '{"info":"验证码输入有误","status":"n"}';
		}
		
		break;

}