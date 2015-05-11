<?php
defined('IN_TS') or die('Access Denied.');


switch($ts){

	case "":

		$title = '找回登陆密码';
		include template("forgetpwd");

		break;
	
	//执行登录
	case "do":
		
		$js = intval($_GET['js']);
		
		
	
		if($_POST['token'] != $_SESSION['token']) {
			getJson('非法操作！',$js);
		}
	
		$email	= trim($_POST['email']);
		
		$emailNum = $new['user']->findCount('user',array(
			'email'=>$email,
		));
		
		if($email==''){
			getJson('Email输入不能为空^_^',$js);
		}elseif($emailNum == '0'){
			getJson("Email不存在，你可能还没有注册^_^",$js);
		}else{
		
			//加密
			$resetpwd = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);

			$new['user']->update('user',array(
				'email'=>$email,
			),array(
				'resetpwd'=>$resetpwd,
			));
			
			//发送邮件
			$subject = $TS_SITE['site_title'].'会员密码找回';
			
			$content = '您的登陆信息：<br />Email：'.$email.'<br />重设密码链接：<br /><a href="'.$TS_SITE['site_url'].'index.php?app=user&ac=resetpwd&mail='.$email.'&set='.$resetpwd.'">'.$TS_SITE['site_url'].'index.php?app=user&ac=resetpwd&mail='.$email.'&set='.$resetpwd.'</a>';
			
			$result = aac('mail')->postMail($email,$subject,$content);
			
			if($result == '0'){
				getJson('找回密码所需信息不完整^_^',$js);
			}elseif($result == '1'){
				getJson('系统已经向你的邮箱发送了邮件，请尽快查收^_^',$js);
			}
			
		}
		
		break;
		
}