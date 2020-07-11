<?php
defined('IN_TS') or die('Access Denied.');

if($TS_SITE['isinvite']==2){
	tsNotice('暂不开放用户注册！');
}

//用户注册
switch($ts){
	case "":
		if(intval($TS_USER['userid']) > 0) {
            header('Location: '.SITE_URL);exit;
        }

        #如果网站只采用手机号注册，就跳转到手机号注册
        if($TS_SITE['regtype']==1){
            header('Location: '.tsUrl('user','phone'));exit;
        }
		
		//邀请用户ID
		$fuserid = intval($_GET['fuserid']);
	
		$title = '注册';
		
		include template("register");
		break;

	case "do":

		//用于JS提交验证
		$js = intval($_GET['js']);
	
		$email		= trim($_POST['email']);
		$pwd			= trim($_POST['pwd']);
		$repwd		= trim($_POST['repwd']);
		$username		= t($_POST['username']);
		
		$fuserid = intval($_POST['fuserid']);
		
		$authcode = strtolower($_POST['authcode']);


        //检测垃圾Email后缀
        $arrEmail = explode('@',$email);
        if($arrEmail[1]=='chacuo.net' || $arrEmail[1]=='mail.ru' || $arrEmail[1]=='yandex.ru' || $arrEmail[1]=='yandex.com' || $arrEmail[1]=='027168.net' || $arrEmail[1]=='027168.com'){
            getJson('Fuck you every day！',$js);
        }
		
		
		/*禁止以下IP用户登陆或注册*/
        /*
		$arrIp = aac('system')->antiIp();
		if(in_array(getIp(),$arrIp)){
			getJson('你的IP已被锁定，暂无法登录！',$js);
		}
        */
		
		
		//是否开启邀请注册
		if($TS_SITE['isinvite']=='1'){
		
			$invitecode = trim($_POST['invitecode']);

			if($invitecode == '') getJson('邀请码不能为空！',$js);

			$codeNum = $new['user']->findCount('user_invites',array(
				'invitecode'=>$invitecode,
				'isused'=>0,
			));
			
			if($codeNum == 0) getJson('邀请码已经被使用，请更换其他邀请码！',$js);
		
		}

		
		if($email=='' || $pwd=='' || $repwd=='' || $username==''){
		
			getJson('所有必选项都不能为空！',$js);
		
		}
		
		if(valid_email($email) == false){
		
			getJson('Email邮箱输入有误',$js);
			
		}

		#判断Email是否存在
        $isEmail = $new['user']->findCount('user',array(
            'email'=>$email,
        ));

		if($isEmail > 0){
			getJson('Email已经注册',$js);
		}
		
		if($pwd != $repwd){
			getJson('两次输入密码不正确！',$js);
		}

		if(count_string_len($username) < 4 || count_string_len($username) > 20){
			getJson('姓名长度必须在4和20之间',$js);
		}

		#用户名敏感词
        $username = antiWord ($username);

		#判断用户名是否存在
        $isUserName = $new['user']->findCount('user_info',array(
            'username'=>$username,
        ));

		if($isUserName > 0){
			getJson('用户名已经存在，请换个用户名！',$js);
		}
		
		if($TS_SITE['isauthcode']){
            if ($authcode != $_SESSION['verify']) {
                getJson('验证码输入有误，请重新输入！', $js);
            }
		}
		
		
		$new['user']->register($email,$username,$pwd,$fuserid,$invitecode);


		//对积分进行处理
		aac('user')->doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts']);
		
		//跳转
		getJson('登录成功！',$js,2,SITE_URL);
		
	
		break;
		
}