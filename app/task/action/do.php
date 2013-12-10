<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

switch($ts){

	//上传头像
	case "face":
	
		$strUser = $new['task']->find('user_info',array(
			'userid'=>$userid,
		));
		
		if($strUser['face']){
		
			$new['task']->replace('task_user',array(
				'userid'=>$userid,
				'taskkey'=>'face',
			),array(
				'userid'=>$userid,
				'taskkey'=>'face',
				'addtime'=>date('Y-m-d H:i:s'),
			));
		
		}else{
			
			header('Location: '.tsUrl('user','set',array('ts'=>'face')));
			exit;
			
		}
		
		header('Location: '.tsUrl('task'));
		exit;
	
		break;

}