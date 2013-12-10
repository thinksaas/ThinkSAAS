<?php
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = $new['user']->isLogin();

$strUser = $new['user']->find('user_info',array(
	'userid'=>$userid,
));

switch($ts){

	//系统头像
	case "face":
		
		//更细系统头像
		$face = intval($_POST['face']);
		if($face == 0) tsNotice('请选择头像！');
		
		//删除头像
		if($strUser['path']){
			unlink('uploadfile/user/'.$strUser['face']);
		}		
		
		$new['user']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'path'=>'',
			'face'=>'face/'.$face.'.jpg',
		));
		
		tsNotice('设置头像成功！');
		
		break;
	
	//设置头像
	case "setface":

		//上传
		$arrUpload = tsUpload($_FILES['picfile'],$userid,'user',array('jpg','gif','png'));
		
		if($arrUpload){

			$new['user']->update('user_info',array(
				'userid'=>$userid,
			),array(
				'path'=>$arrUpload['path'],
				'face'=>$arrUpload['url'],
			));
			
			
			//更新缓存头像
			$_SESSION['tsuser']['face'] = $arrUpload['url'];
			$_SESSION['tsuser']['path'] = $arrUpload['path'];
			
			tsDimg($arrUpload['url'],'user','120','120',$arrUpload['path']);
			tsDimg($arrUpload['url'],'user','48','48',$arrUpload['path']);
			tsDimg($arrUpload['url'],'user','32','32',$arrUpload['path']);
			tsDimg($arrUpload['url'],'user','24','24',$arrUpload['path']);
			
			$filesize=abs(filesize('uploadfile/user/'.$arrUpload['url']));
			if($filesize<=0){
				$new['user']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'path'=>'',
					'face'=>'',
				));
				
				tsNotice('上传头像失败，你可以使用系统默认头像！');
				
			}else{ 
				tsNotice("头像修改成功！");
			}
			
		}else{
			tsNotice("上传出问题啦！");
		}

		break;
	
	//基本信息设置
	case "setbase":
	
		$username = t($_POST['username']);
		$signed = t($_POST['signed']);
		$phone = t($_POST['phone']);
		$blog = t($_POST['blog']);
		$about = t($_POST['about']);
		$sex = intval($_POST['sex']);

		if($TS_USER['user'] == '') {
		
			tsNotice("机房重地，闲人免进！");
		
		}
		if($username == '') {
		
			tsNotice("不管做什么都需要有一个名号吧^_^");
		
		}
		if(strlen($username) < 4 || strlen($username) > 20) {
		
			tsNotice("用户名长度必须在4到20字符之间!");
		
		}
		
		if($username != $strUser['username']){
		
			if($TS_APP['options']['banuser']){
			
				$arrUserName = explode('|',$TS_APP['options']['banuser']);
				if(in_array($username,$arrUserName)){
					tsNotice("用户名已经存在，请换个用户名！");
				}
			
			}
			
			$isUserName = $new['user']->findCount('user_info',array(
				'username'=>$username,
			));
			
			if($isUserName > 0) {
			
				tsNotice("用户名已经存在，请换个用户名！");
			
			}
		}
		
		if(intval($TS_USER['user']['isadmin'])==0){
			//过滤内容开始
			aac('system')->antiWord($username);
			aac('system')->antiWord($signed);
			aac('system')->antiWord($phone);
			aac('system')->antiWord($blog);
			aac('system')->antiWord($about);
			//过滤内容结束
		}
		
		//更新数据
		$new['user']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'username' => $username,
			'sex'	=> $sex,
			'signed'	=> $signed,
			'phone'	=> $phone,
			'blog'	=> $blog,
			'about' => $about,
		));

		tsNotice("基本资料更新成功！");

		break;
		
	//修改常居地
	case "setcity":
		
		$province = intval($_POST['province']);
		$city = intval($_POST['city']);
		$area = intval($_POST['area']);

		$new['user']->update('user_info',array(
			'userid'=>$userid,
		),array(
		
			'province'=>$province,
			'city'=>$city,
			'area'=>$area,
		
		));

		tsNotice("常居地更新成功！");
		
		break;
		
	//修改用户密码 
	case "setpwd":
		
		$theUser = $new['user']->find('user',array(
			'userid'=>$userid,
		));
		
		$oldpwd = trim($_POST['oldpwd']);
		$newpwd = trim($_POST['newpwd']);
		$renewpwd = trim($_POST['renewpwd']);
		
		if($oldpwd == '' || $newpwd=='' || $renewpwd=='') tsNotice("所有项都不能为空！");
		
		if($newpwd != $renewpwd) tsNotice('两次输入新密码密码不一样！');
		
		//更新密码
		if(md5($theUser['salt'].$oldpwd) != $theUser['pwd']) tsNotice("旧密码输入有误！");
		
		$salt = md5(rand());
		
		$new['user']->update('user',array(
			'userid'=>$userid,
		),array(
			'pwd'=>md5($salt.$newpwd),
			'salt'=>$salt,
		));

		 tsNotice("密码修改成功！");
		
		break;
		
	//修改登录帐号
	case "setemail":
		
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
				'userid'=>$userid,
			),array(
				'email'=>$email,
			));
			
			//修改信息并将用户设为未验证状态
			$new['user']->update('user_info',array(
				'userid'=>$userid,
			),array(
				'email'=>$email,
				'isverify'=>'0',
			));
			
			tsNotice('Email帐号修改成功，下次请用'.$email.'登录网站！');
			
		}else{
			tsNotice('新Email帐号不能和旧Email帐号一样！');
		}
		
		break;
} 