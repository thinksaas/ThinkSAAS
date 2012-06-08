<?php 
defined('IN_TS') or die('Access Denied.');
//用户是否登录

switch($ts){

	case "":
	
		$userid = aac('user')->isLogin();

		$strUser = $new['user']->find('user_info',array(
			'userid'=>$userid,
		));
	
		$title = '用户验证';
		include template('verify');
		break;

	//发送验证
	case "post":
		$userid = aac('user')->isLogin();

		$strUser = $new['user']->find('user_info',array(
			'userid'=>$userid,
		));
		if($strUser['verifycode']==''){
			$verifycode = random(11);
			$db->query("update ".dbprefix."user_info set `verifycode`='$verifycode' where `userid`='$userid'");
		}else{
			$verifycode = $strUser['verifycode'];
		}

		$email = $strUser['email'];

		//发送邮件
		$subject = $TS_SITE['base']['site_title'].'会员真实性验证';
		$content = '尊敬的'.$strUser['username'].'，<br />请点击以下链接进行会员验证：<a href="'.$TS_SITE['base']['site_url'].'index.php?app=user&ac=verify&ts=do&email='.$email.'&verifycode='.$verifycode.'">'.$TS_SITE['base']['site_url'].'index.php?app=user&ac=verify&ts=do&email='.$email.'&verifycode='.$verifycode.'</a>';

		$result = aac('mail')->postMail($email,$subject,$content);

		if($result == '0'){
			tsNotice("验证失败，可能是你的Email邮箱错误哦^_^");
		}elseif($result == '1'){
			tsNotice("系统已经向你的邮箱发送了验证邮件，请尽快查收^_^");
		}
		break;
		
	//接收验证 
	case "do":
		$email = $_GET['email'];
		$verifycode = $_GET['verifycode'];
		
		$verify = $new['user']->findCount('user_info',array(
			'email'=>$email,
			'verifycode'=>$verifycode,
		));
		
		if($verify > 0){

			$new['user']->update('user_info',array(
				'email'=>$email,
			),array(
				'isverify'=>'1',
			));
			tsNotice("Email验证成功！点击返回首页！",'点击回首页！',SITE_URL);
		}else{
			tsNotice("Email验证失败！");
		}
		
		break;
}