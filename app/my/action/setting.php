<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

	
	case "base":
		$title = '基本设置';
		include template("setting_base");
		break;
		
	case "basedo":
	
		$username = t($_POST['username']);
		$signed = t($_POST['signed']);
		$phone = t($_POST['phone']);
		$blog = t($_POST['blog']);
		$about = t($_POST['about']);
		$sex = t($_POST['sex']);

		if($TS_USER == '') {
		
			tsNotice("机房重地，闲人免进！");
		
		}
		if($username == '') {
		
			tsNotice("不管做什么都需要有一个名号吧^_^");
		
		}
		if(strlen($username) < 4 || strlen($username) > 20) {
		
			tsNotice("用户名长度必须在4到20字符之间!");
		
		}
		
		if($username != $strUser['username']){
		
			if($TS_APP['banuser']){
			
				$arrUserName = explode('|',$TS_APP['banuser']);
				if(in_array($username,$arrUserName)){
					tsNotice("用户名已经存在，请换个用户名！");
				}
			
			}
			
			$isUserName = $new['my']->findCount('user_info',array(
				'username'=>$username,
			));
			
			if($isUserName > 0) {
			
				tsNotice("用户名已经存在，请换个用户名！");
			
			}
		}
		
		if(intval($TS_USER['isadmin'])==0){
			//过滤内容开始
			aac('system')->antiWord($username);
			aac('system')->antiWord($signed);
			aac('system')->antiWord($phone);
			//aac('system')->antiWord($blog);
			aac('system')->antiWord($about);
			//过滤内容结束
		}


        //签名中禁止写URL,Email
        /*
        if(filter_var($signed, FILTER_SANITIZE_URL) || filter_var($signed, FILTER_VALIDATE_EMAIL)){
            tsNotice('签名不合法！请修改后再提交！');
        }

        if(filter_var($about, FILTER_SANITIZE_URL) || filter_var($about, FILTER_VALIDATE_EMAIL)){
            tsNotice('个人介绍不合法！请修改后再提交！');
        }
        */


		
		//更新数据
		$new['my']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'username' => $username,
			'sex'	=> $sex,
			'signed'	=> $signed,
			'phone'	=> $phone,
			'about' => $about,
		));

		#更新session用户名
        $_SESSION['tsuser']['username'] = $username;

		tsNotice("基本资料更新成功！");
	
		break;

		
	case "face":

		$title = '头像设置';
		
		$arrFace = tsScanDir('uploadfile/user/face',1);
		
		include template("setting_face");

		break;
	//执行上传头像
	case "facedo":
	
		if($_FILES['photo']){
			
			//上传
			$arrUpload = tsUpload($_FILES['photo'],$userid,'user',array('jpg','gif','png','jpeg'));
			
			if($arrUpload){

				$new['my']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'path'=>$arrUpload['path'],
					'face'=>$arrUpload['url'],
				));
				
				$filesize=abs(filesize('uploadfile/user/'.$arrUpload['url']));
				if($filesize<=0){
					$new['my']->update('user_info',array(
						'userid'=>$userid,
					),array(
						'path'=>'',
						'face'=>'',
					));
					
					tsNotice('上传头像失败，你可以使用系统默认头像！');
					
				}else{ 
				
					//更新缓存头像
					$_SESSION['tsuser']['face'] = $arrUpload['url'];
					$_SESSION['tsuser']['path'] = $arrUpload['path'];
					
					tsDimg($arrUpload['url'],'user','120','120',$arrUpload['path']);
				
					header('Location: '.tsUrl('my','setting',array('ts'=>'face')));
				}
				
			}else{
				tsNotice('头像修改失败');
			}
			
		}
		
		break;
	
	//设置密码
	case "pwd":
	
		$title = '密码修改';
		include template("setting_pwd");

		break;
		
	case "pwddo":
	
		$theUser = $new['my']->find('user',array(
			'userid'=>$strUser['userid'],
		));
		
		$oldpwd = trim($_POST['oldpwd']);
		$newpwd = trim($_POST['newpwd']);
		$renewpwd = trim($_POST['renewpwd']);
		
		if($oldpwd == '' || $newpwd=='' || $renewpwd=='') tsNotice("所有项都不能为空！");
		
		if($newpwd != $renewpwd) tsNotice('两次输入新密码密码不一样！');
		
		//更新密码
		if(md5($theUser['salt'].$oldpwd) != $theUser['pwd']) tsNotice("旧密码输入有误！");
		
		$salt = md5(rand());
		
		$new['my']->update('user',array(
			'userid'=>$strUser['userid'],
		),array(
			'pwd'=>md5($salt.$newpwd),
			'salt'=>$salt,
		));

		 tsNotice("密码修改成功！");
	
		break;
		
	//修改登录Email
	case "email":
		$title = '修改登录Email';
		include template('setting_email');
		break;
		
	case "emaildo":
		
		$email = trim($_POST['email']);
		
		if($email=='') tsNotice('Email不能为空！');
		
		if(valid_email($email) == false) tsNotice('Email输入有误！');
		
		if($email != $strUser['email']){
			$emailNum = $new['my']->findCount('user',array(
				'email'=>$email,
			));
			
			if($emailNum > 0) tsNotice("Email帐号已经存在，请换个其他Email帐号！");
			
			//更新Email
			$new['my']->update('user',array(
				'userid'=>$strUser['userid'],
			),array(
				'email'=>$email,
			));
			
			//修改信息并将用户设为未验证状态
			$new['my']->update('user_info',array(
				'userid'=>$strUser['userid'],
			),array(
				'email'=>$email,
				'isverify'=>'0',
			));
			
			tsNotice('Email帐号修改成功，下次请用'.$email.'登录网站！');
			
		}else{
			tsNotice('新Email帐号不能和旧Email帐号一样！');
		}
	
		break;
		
	//设置常居地 
	case "city":
		
		$title = '常居地修改';
		include template("setting_city");
		break;
		
	case "citydo":
	
		$province = trim($_POST['province']);
		$city = trim($_POST['city']);
		

		$new['my']->update('user_info',array(
			'userid'=>$userid,
		),array(
		
			'province'=>$province,
			'city'=>$city,
		
		));

		tsNotice("常居地更新成功！");
	
		break;
	
	//个人标签
	case "tag":
	
		$arrTag = aac('tag')->getObjTagByObjid('user','userid',$userid);
	
		$title = '个人标签修改';
		include template("setting_tag");
		break;
		
	case "tagdo":
		break;

}