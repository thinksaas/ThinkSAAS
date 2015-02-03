<?php
defined('IN_TS') or die('Access Denied.');
//登录
switch($ts){
	case "":
		$title = '登录后台';
		include template("login");
		break;
		
	case "do":
	
		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}
		
		$email = trim($_POST['email']);
		$pwd = trim($_POST['pwd']);
		$cktime = $_POST['cktime'];
		
		if($email=='' || $pwd=='') qiMsg("所有输入项都不能为空^_^");
		
		$countAdmin = $new['system']->findCount('user',array(
			'email'=>$email,
		));
		
		if($countAdmin == 0) qiMsg('用户Email不存在！');
		
		$strAdmin = $new['system']->find('user',array(
			'email'=>$email,
		));
			
		if(md5($strAdmin['salt'].$pwd)!==$strAdmin['pwd']) tsNotice('用户密码错误！');
		
		$strAdminInfo = $new['system']->find('user_info',array(
			'email'=>$email,
		),'userid,username,isadmin');
		
		if($strAdminInfo['isadmin'] != 1) qiMsg("你无权登录后台管理！");
		
		$_SESSION['tsadmin'] = $strAdminInfo;
		
		header("Location: ".SITE_URL."index.php?app=system");
		
		break;
		
	//退出 
	case "out":
		unset($_SESSION['tsadmin']);
		
		header("Location: ".SITE_URL."index.php?app=system&ac=login");
		
		break;
}