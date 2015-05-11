<?php 
defined('IN_TS') or die('Access Denied.');
//用户是否登录

switch($ts){

	case "":
	
		$userid = aac('user')->isLogin();

		$strUser = $new['user']->getOneUser($userid);
	
		$title = '用户验证';
		include template('verify');
		break;

	//发送验证
	case "post":
	
		if($_GET['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}
	
		$userid = aac('user')->isLogin();

		$strUser = $new['user']->find('user_info',array(
			'userid'=>$userid,
		));
		if($strUser['verifycode']==''){
			$verifycode = random(11);
			$new['user']->update('user_info',array(
				'userid'=>$userid,
			),array(
				'verifycode'=>$verifycode,
			));
		}else{
			$verifycode = $strUser['verifycode'];
		}

		$email = $strUser['email'];

		//发送邮件
		$subject = $TS_SITE['site_title'].'会员真实性验证';
		$content = '尊敬的'.$strUser['username'].'，<br />请点击以下链接进行会员验证：<a href="'.$TS_SITE['link_url'].'index.php?app=user&ac=verify&ts=do&email='.$email.'&verifycode='.$verifycode.'">'.$TS_SITE['link_url'].'index.php?app=user&ac=verify&ts=do&email='.$email.'&verifycode='.$verifycode.'</a>';

		$result = aac('mail')->postMail($email,$subject,$content);

		if($result == '0'){
			tsNotice("验证失败，可能是你的Email邮箱错误哦^_^");
		}elseif($result == '1'){
			tsNotice("系统已经向你的邮箱发送了验证邮件，请尽快查收^_^");
		}
		break;
		
	//接收验证 
	case "do":
		$email = tsFilter($_GET['email']);
		$verifycode = tsFilter($_GET['verifycode']);
		
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
			tsNotice("Email验证成功!",'点击回首页！',SITE_URL);
		}else{
			tsNotice("Email验证失败！");
		}
		
		break;
	
	//修改Email
	case "setemail":
		
		$userid = aac('user')->isLogin();
		
		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}
		
		$strUser = $new['user']->getOneUser($userid);
		
		$email = trim($_POST['email']);
		
		if($email=='') tsNotice('Email不能为空！');
		
		if(valid_email($email) == false) tsNotice('Email输入有误！');
		
		if($email != $strUser['email']){
			$emailNum = $new['user']->findCount('user',array(
				'email'=>$email,
			));
			
			if($emailNum > 0) tsNotice("Email帐号已经存在，请换个其他Email帐号！");
			
			//更新Email
			$new['user']->update('user',array(
				'userid'=>$strUser['userid'],
			),array(
				'email'=>$email,
			));
			
			//修改信息并将用户设为未验证状态
			$new['user']->update('user_info',array(
				'userid'=>$strUser['userid'],
			),array(
				'email'=>$email,
				'isverify'=>'0',
			));
			
			tsNotice('Email帐号修改成功，请返回重新验证！');
			
		}else{
			tsNotice('新Email帐号不能和旧Email帐号一样！');
		}
		
		break;
		
	//必须上传头像
	case "face":
	
		$userid = aac('user')->isLogin();

		$strUser = $new['user']->getOneUser($userid);
		
		$title = '上传头像';
		include template('verify_face');
		break;
		
	case "facedo":
		
		$userid = aac('user')->isLogin();
		
		if($_FILES['picfile']){
			
			//上传
			$arrUpload = tsUpload($_FILES['picfile'],$userid,'user',array('jpg','gif','png'));
			
			if($arrUpload){

				$new['user']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'path'=>$arrUpload['path'],
					'face'=>$arrUpload['url'],
				));
				
				$filesize=abs(filesize('uploadfile/user/'.$arrUpload['url']));
				if($filesize<=0){
					$new['user']->update('user_info',array(
						'userid'=>$userid,
					),array(
						'path'=>'',
						'face'=>'',
					));
					
					tsNotice('上传头像失败！');
					
				}else{ 
				
					//更新缓存头像
					$_SESSION['tsuser']['face'] = $arrUpload['url'];
					$_SESSION['tsuser']['path'] = $arrUpload['path'];
					
					tsDimg($arrUpload['url'],'user','120','120',$arrUpload['path']);
				
					header('Location: '.tsUrl('user','verify',array('ts'=>'face')));
				}
				
			}else{
				tsNotice('头像修改失败');
			}
			
		}
		
		break;
}