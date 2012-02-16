<?php
defined('IN_TS') or die('Access Denied.');
//用户注册
switch($ts){
	case "":
		if(intval($TS_USER['user']['userid']) > 0) tsNotice("请退出后再注册！");
		
		//邀请用户ID
		$fuserid = intval($_GET['fuserid']);
	
		$title = '注册';
		
		//调出省份数据
		$arrOne = $db->findAll("select * from ".dbprefix."area where referid='0'");
		
		include template("register");
		break;

	case "do":
		$email		= trim($_POST['email']);
		$pwd			= trim($_POST['pwd']);
		$repwd		= trim($_POST['repwd']);
		$username		= t($_POST['username']);
		
		$fuserid = intval($_POST['fuserid']);
		
		$authcode = strtoupper($_POST['authcode']);
		
		//是否开启邀请注册
		if($TS_APP['options']['isregister']=='1'){
		
			$invitecode = trim($_POST['invitecode']);
			if($invitecode == '') tsNotice("邀请码不能为空！");
			$codeNum = $db->find("select count(*) from ".dbprefix."user_invites where `invitecode`='$invitecode' and `isused`='0'");
			if($codeNum['count(*)'] == 0) tsNotice("邀请码无效，请更换邀请码！");
		
		}

		$isEmail = $db->find("select count(*) from ".dbprefix."user where `email`='$email'");
		
		$isUserName = $db->find("select count(*) from ".dbprefix."user_info where `username`='$username'");
		
		if($email=='' || $pwd=='' || $repwd=='' || $username==''){
		
			tsNotice('所有必选项都不能为空！');
		
		}elseif(valid_email($email) == false){
		
			tsNotice('Email邮箱输入有误!');
			
		}elseif($isEmail['count(*)'] > 0){
			tsNotice('Email已经注册^_^');
		}elseif($pwd != $repwd){
			tsNotice('两次输入密码不正确！');
		}elseif(strlen($username) < 4 || strlen($username) > 20){
			tsNotice('姓名长度必须在4和20之间!');
		}elseif($isUserName['count(*)'] > 0){
			tsNotice("用户名已经存在，请换个用户名！");
		}elseif($authcode != $_SESSION['authcode']){
			tsNotice("验证码输入有误，请重新输入！");
		}else{
			
			$salt = md5(rand());
			
			$userid = $db->create('user',array(
				'pwd'=>md5($salt.$pwd),
				'salt'=>$salt,
				'email'=>$email,
			));
			
			//积分
			$db->create('user_scores',array(
				'userid'=>$userid,
				'scorename'=>'注册',
				'score'=>1000,
				'addtime'=>time(),
			));
		
			
			//插入用户信息
			$db->create(dbprefix.'user_info',array(
				'userid'			=> $userid,
				'fuserid'	=> $fuserid,
				'username' 	=> $username,
				'email'		=> $email,
				'ip'			=> getIp(),
				'count_score'	=> '1000',
				'addtime'	=> time(),
				'uptime'	=> time(),
			));
			
			//默认加入小组
			$isgroup = $db->find("select optionvalue from ".dbprefix."user_options where optionname='isgroup'");
			if($isgroup['optionvalue'] != ''){
				$arrGroup = explode(',',$isgroup['optionvalue']);
				foreach($arrGroup as $item){
				
					$groupusernum = $db->findCount('group_users',array(
						'userid'=>$userid,
						'groupid'=>$item,
					));
					
					if($groupusernum == '0'){
						
						$db->create('group_users',array(
							'userid'=>$userid,
							'groupid'=>$item,
							'addtime'=>time(),
						));
						
						//统计更新
						$count_user = $db->findCount('group_users',array(
							'groupid'=>$item,
						));

						$db->update('group',array(
							'count_user'=>$count_user,
						),array(
							'groupid'=>$item,
						));
						
					}
				}
			}
			
			//用户信息
			$userData = $db->find("select * from ".dbprefix."user_info where userid='$userid'");
			
			//用户session信息
			$sessionData = array(
				'userid' => $userData['userid'],
				'username'	=> $userData['username'],
				'areaid'	=> $userData['areaid'],
				'path'	=> $userData['path'],
				'face'	=> $userData['face'],
				'count_score'	=> $userData['count_score'],
				'isadmin'	=> $userData['isadmin'],
				'uptime'	=> $userData['uptime'],
			);
			$_SESSION['tsuser']	= $sessionData;
			
			//发送系统消息(恭喜注册成功)
			$msg_userid = '0';
			$msg_touserid = $userid;
			$msg_content = '亲爱的 '.$username.' ：<br />您成功加入了 '
										.$TS_SITE['base']['site_title'].'<br />在遵守本站的规定的同时，享受您的愉快之旅吧!';
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
			
			//注销邀请码
			if($TS_APP['options']['isregister']=='1'){

				$db->update('user_invites',array(
					'isused'=>1,
				),array(
					'invitecode'=>$invitecode,
				));
				
			}
			
			//跳转
			header('Location: '.SITE_URL.'index.php');
			
		}
		break;
}