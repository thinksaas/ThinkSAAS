<?php
defined('IN_TS') or die('Access Denied.');

$userid = $new['mobile']->isLogin();

switch($ts){
	
	case "":
	
		$strUser = aac('user')->getOneUser($userid);
		include template('user');
		break;
		
	case "group":
		
		$arrGroupsList = $new['mobile']->findAll('group_user',array(
			'userid'=>$userid,
		),null,'groupid');


		foreach($arrGroupsList as $key=>$item){
			$arrGroup[] = $new['mobile']->find('group',array(
				'groupid'=>$item['groupid'],
			));
		}
		
		$arrHeadButton = array(
			'left'=>array(
				'title'=>'返回',
				'url'=>tsUrl('mobile','user'),
			),
/* 			'right'=>array(
				'title'=>'评论',
				'url'=>'#',
			), */
		);
		
		include template('user_group');
		break;
		
	case "topic":
	
		$arrTopic = $new['mobile']->findAll('group_topic',array(
			'userid'=>$userid,
		),'addtime desc',null,30);
		
		foreach($arrTopic as $key=>$item){
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
		}
		
		$arrHeadButton = array(
			'left'=>array(
				'title'=>'返回',
				'url'=>tsUrl('mobile','user'),
			),
/* 			'right'=>array(
				'title'=>'评论',
				'url'=>'#',
			), */
		);
		
		
		include template('user_topic');
		break;
	
}