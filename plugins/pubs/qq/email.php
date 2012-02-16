<?php
//email验证页
switch($ts){
	case "":
		$email = $_GET['email'];
		$isemail = $db->once_num_rows("select * from ".dbprefix."user where email='$email'");
		if($isemail > 0){
		
			$title = "绑定QQ帐号";
			include "email.html";
		
		}else{
			header("Location: ".SITE_URL."index.php");
		}
		break;
		
	case "do":
		$email = trim($_POST['email']);
		$pwd = trim($_POST['pwd']);
		$strUser = $db->once_fetch_assoc("select * from ".dbprefix."user where email='$email'");
		
		if($pwd == ''){
			qiMsg("密码不能为空！");
		}elseif($strUser['pwd'] != md5($pwd)){
			qiMsg("密码输入有误！");
		}else{
		
			$db->query("update ".dbprefix."user_info set `qq_openid`='".$_SESSION['openid']."',`qq_token`='".$_SESSION['token']."',`qq_secret`='".$_SESSION['secret']."' where email='$email'");
		
			$userData = $db->once_fetch_assoc("select * from ".dbprefix."user_info where email='$email'");
			
			//发送系统消息(恭喜注册成功)
			$userid = $userData['userid'];
			$username = $userData['username'];
			
			$msg_userid = '0';
			$msg_touserid = $userid;
			$msg_content = '亲爱的 '.$username.' ：<br />恭喜你成功绑定QQ登录信息。<br />现在你除了可以用Email登录，同时还可以使用QQ登录！<br />感谢你对ThinkSAAS的支持！';
			aac('message',$db)->sendmsg($msg_userid,$msg_touserid,$msg_content);
			
			$_SESSION['tsuser']	= $userData;
		
			header("Location: ".SITE_URL."index.php");
			
		}
		
		break;
}