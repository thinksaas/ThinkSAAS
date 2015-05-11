<?php
defined('IN_TS') or die('Access Denied.');

//用户注册
switch($ts){
	case "":
		if(intval($TS_USER['userid']) > 0) tsNotice("请退出后再注册！");
		
		//邀请用户ID
		$fuserid = intval($_GET['fuserid']);
	
		$title = '注册';
		
		include template("register");
		break;

	case "do":
	
		//用于JS提交验证
		$js = intval($_GET['js']);
	
		if($_POST['token'] != $_SESSION['token']) {
			getJson('非法操作！',$js);
		}
	
		$email		= trim($_POST['email']);
		$pwd			= trim($_POST['pwd']);
		$repwd		= trim($_POST['repwd']);
		$username		= t($_POST['username']);
		
		$fuserid = intval($_POST['fuserid']);
		
		$authcode = strtolower($_POST['authcode']);
		
		
		/*禁止以下IP用户登陆或注册*/
		$arrIp = aac('system')->antiIp();
		if(in_array(getIp(),$arrIp)){
			getJson('你的IP已被锁定，暂无法登录！',$js);
		}
		
		
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

		$isEmail = $new['user']->findCount('user',array(
			'email'=>$email,
		));
		
		$isUserName = $new['user']->findCount('user_info',array(
			'username'=>$username,
		));
		
		if($email=='' || $pwd=='' || $repwd=='' || $username==''){
		
			getJson('所有必选项都不能为空！',$js);
		
		}
		
		if(valid_email($email) == false){
		
			getJson('Email邮箱输入有误',$js);
			
		}
		
		if($isEmail > 0){
			getJson('Email已经注册',$js);
		}
		
		if($pwd != $repwd){
			getJson('两次输入密码不正确！',$js);
		}
		
		
		if(count_string_len($username) < 4 || count_string_len($username) > 20){
			getJson('姓名长度必须在4和20之间',$js);
		}
		
		if($isUserName > 0){
			getJson('用户名已经存在，请换个用户名！',$js);
		}
		
		if($TS_SITE['isauthcode']){
			if($authcode != $_SESSION['verify']){
				getJson('验证码输入有误，请重新输入！',$js);
			}
		}
		
		$salt = md5(rand());
		
		$userid = $new['user']->create('user',array(
			'pwd'=>md5($salt.$pwd),
			'salt'=>$salt,
			'email'=>$email,
		));
		
		//插入用户信息			
		$new['user']->create('user_info',array(
			'userid'			=> $userid,
			'fuserid'	=> $fuserid,
			'username' 	=> $username,
			'email'		=> $email,
			'ip'			=> getIp(),
			'addtime'	=> time(),
			'uptime'	=> time(),
		));
		
		//默认加入小组
		$isGroup = $new['user']->find('user_options',array(
			'optionname'=>'isgroup',
		));
		
		if($isGroup['optionvalue']){
			$arrGroup = explode(',',$isGroup['optionvalue']);
			
			if($arrGroup){
				foreach($arrGroup as $key=>$item){
					$groupUserNum = $new['user']->findCount('group_user',array(
						'userid'=>$userid,
						'groupid'=>$item,
					));
					
					if($groupUserNum == 0){
						$new['user']->create('group_user',array(
							'userid'=>$userid,
							'groupid'=>$item,
							'addtime'=>time(),
						));
						
						//统计更新
						$count_user = $new['user']->findCount('group_user',array(
							'groupid'=>$item,
						));
						
						$new['user']->update('group',array(
							'groupid'=>$item,
						),array(
							'count_user'=>$count_user,
						));
						
					}
				}
			}
		}
		
		//用户信息
		$userData = $new['user']->find('user_info',array(
			'userid'=>$userid,
		),'userid,username,path,face,isadmin,signin,uptime');
		
		//用户session信息
		$_SESSION['tsuser']	= $userData;
		
		//发送消息
		aac('message')->sendmsg(0,$userid,'亲爱的 '.$username.' ：<br />您成功加入了 '.$TS_SITE['site_title'].'<br />在遵守本站的规定的同时，享受您的愉快之旅吧!');
		
		//注销邀请码并将关注邀请用户
		if($TS_SITE['isinvite']=='1'){
			
			//邀请码信息
			$strInviteCode = $new['user']->find('user_invites',array(
				'invitecode'=>$invitecode,
			));
			
			$new['user']->create('user_follow',array(
				'userid'=>$userid,
				'userid_follow'=>$strInviteCode['userid'],
			));
			
			//注销
			$new['user']->update('user_invites',array(
				'invitecode'=>$invitecode,
			),array(
				'isused'=>'1',
			));
		}
		
		//对积分进行处理
		aac('user')->doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts']);
		
		//跳转
		getJson('登录成功！',$js,2,SITE_URL);
		
	
		break;
		
}