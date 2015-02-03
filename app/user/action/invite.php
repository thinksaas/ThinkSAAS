<?php
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

$strUser = $new['user']->find('user_info',array(
	'userid'=>$userid,
));

//邀请好友
switch($ts){
	case "":
		
		//计算是否还有邀请码
		$codeNum = $new['user']->findCount('user_invites',array(
			'userid'=>$userid,
			'isused'=>0,
		));
		
		$arrCode = $new['user']->findAll('user_invites',array(
			'userid'=>$userid,
			'isused'=>0,
		));

		$title = '邀请码';
		include template("invite");
		
		break;
		
	//取邀请码
	case "code":
		
		//计算是否还有邀请码
		$codeNum = $new['user']->findCount('user_invites',array(
			'userid'=>$userid,
			'isused'=>0,
		));
		
		if($codeNum == 0){
		
			//当数据库中没码的时间生成10个码
			for($i=1;$i<=10;$i++){
				
				$new['user']->create('user_invites',array(
					'userid'=>$userid,
					'invitecode'=>random(18).$userid,
					'addtime'=>time(),
				));

			}

		}
		
		header('Location: '.tsUrl('user','invite'));
		
		break;
}