<?php
defined('IN_TS') or die('Access Denied.');
//程序主体
switch($ts){
	case "":
		if(intval($TS_USER['userid']) > 0) {
            header('Location: '.SITE_URL);exit;
        }
		
		//记录上次访问地址
		$jump = $_SERVER['HTTP_REFERER'];

		$title = '登录';
		include template("login");
		break;
	
	//执行登录
	case "do":
		
		//用于JS提交验证
		$js = intval($_GET['js']);

        $ad = intval($_POST['ad']);
		
		/*禁止以下IP用户登陆或注册*/
		$arrIp = aac('system')->antiIp();
		if(in_array(getIp(),$arrIp)){
			getJson('你的IP已被锁定，暂无法登录！',$js);
		}
		
		$jump = trim($_POST['jump']);
		
		$email = trim($_POST['email']);
		
		$pwd = trim($_POST['pwd']);
		
		$cktime = $_POST['cktime'];
		
		if($email=='' || $pwd=='') getJson('Email和密码都不能为空！',$js);

		#先判断是否是Email
		if(valid_email($email)==true){

            $strUser = $new['user']->find('user',array(
                'email'=>$email,
            ));

            if($strUser == '') getJson('Email不存在，你可能还没有注册！',$js);

        }else{

		    #判断是否是手机号
            if(isPhone($email)==true){

                $strUser = $new['user']->find('user',array(
                    'phone'=>$email,
                ));

                if($strUser == '') getJson('手机号不存在，你可能还没有注册！',$js);

            }else{
                getJson('账号不存在，你可能还没有注册！',$js);
            }

        }
			
		if(md5($strUser['salt'].$pwd)!==$strUser['pwd']) getJson('密码错误！',$js);
		
		//用户信息
		$userData = $new['user']->find('user_info',array(
			'email'=>$strUser['email'],
		),'userid,username,path,face,isadmin,signin,uptime');
			
		//用户session信息
		$sessionData = array(
			'userid' => $userData['userid'],
			'username'	=> $userData['username'],
			'path'	=> $userData['path'],
			'face'	=> $userData['face'],
			'isadmin'	=> $userData['isadmin'],
			'signin'=>$userData['signin'],
			'uptime'	=> $userData['uptime'],
		);

		$_SESSION['tsuser']	= $sessionData;
		
		//用户userid
		$userid = $userData['userid'];
		
		//一天之内登录只算一次积分
		if($userData['uptime'] < strtotime(date('Y-m-d'))){
			//对积分进行处理
			aac('user')->doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts']);
		}
		
		//更新登录时间，用作自动登录
		$autologin = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
		$new['user']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'ip'=>getIp(),  //更新登录ip
			'autologin'=>$autologin,
			'uptime'=>time(),   //更新登录时间
		));
		
		//记住登录Cookie，根据用户Email和最后登录时间
		 if($cktime != ''){   
			 setcookie("ts_email", $strUser['email'], time()+$cktime,'/');
			 setcookie("ts_autologin", $autologin, time()+$cktime,'/');
		 }

        if($ad==1){
            getJson('登录成功！',$js,2,SITE_URL.'index.php?app=system');
        }

		//跳转
		if($jump != ''){
			getJson('登录成功！',$js,2,$jump);
		}else{
			
			//登陆是否跳转到我的社区
			if($TS_SITE['istomy']){
				getJson('登录成功！',$js,2,tsUrl('my'));
			}else{
				getJson('登录成功！',$js,2,SITE_URL);
			}
			
		}
		
		break;
	
	//退出	
	case "out":
		aac('user')->logout();
		header('Location: '.tsUrl('user','login'));
		
		break;
}