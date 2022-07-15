<?php
defined('IN_TS') or die('Access Denied.');


switch($ts){

	/**
	 * 通过Email找回密码
	 */
	case "":

        if ($GLOBALS['TS_USER']){
            header('Location: '.SITE_URL);
            exit;
        }

		$title = '找回登陆密码';
		include template("forgetpwd");

		break;
	
	/**
	 * 重置Email密码
	 */
	case "resetpwd":

		$js = tsIntval($_GET['js']);


        $email = tsTrim($_POST['email']);
        $pwd	= tsTrim($_POST['pwd']);
        $authcode = strtolower($_POST['authcode']);
        $emailcode = tsTrim($_POST['emailcode']);

        if($email == '' || $pwd=='' || $authcode=='' || $emailcode==''){
            getJson('所有输入项都不能为空',$js);
        }

        if(valid_email($email)==false){
            getJson('Email邮箱输入不正确',$js);
        }

        $strUser = $new['user']->find('user',array(
            'email'=>$email,
        ));

        if($strUser==''){
            getJson("Email邮箱不存在，你可能还没有注册^_^",$js);
        }


        if ($authcode != $_SESSION['verify']) {
            getJson('图片验证码输入有误，请重新输入！', $js);
        }


        #验证手机验证码
        if(aac('pubs')->verifyEmailCode($email,$emailcode)==false){
            getJson('Email验证码输入有误',$js);
        }

        $salt = md5(rand());

        $new['user']->update('user',array(
            'userid'=>$strUser['userid'],
        ),array(
            'pwd'=>md5($salt.$pwd),
            'salt'=>$salt,
        ));


        $new['user']->update('user_info',array(
            'userid'=>$strUser['userid'],
        ),array(
            'email'=>$strUser['email'],
            'isverify'=>'1',
        ));

        $_SESSION['tsuser']['isverify']=1;

        getJson('密码修改成功！',$js,2,tsUrl('user','login'));

		break;

	/**
	 * 通过手机号找回密码
	 */
	case "phone":

		$title = '找回登陆密码';
		include template("forgetpwd_phone");

		break;
	
	//执行登录
	case "do":
		
		$js = tsIntval($_GET['js']);

	
		$email	= tsTrim($_POST['email']);

        if(valid_email($email)==false){
            getJson('Email输入不正确',$js);
        }
		
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