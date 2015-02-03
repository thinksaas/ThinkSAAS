<?php 
defined('IN_TS') or die('Access Denied.');


switch($ts){
	
	//我关注的人
	case "follow":
	
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = tsUrl('my','friend',array('ts'=>'follow','page'=>''));
		$lstart = $page*80-80;
		
		//关注的用户
		$arrUsers = $new['my']->findAll('user_follow',array(
			'userid'=>$strUser['userid'],
		),'addtime desc',null,$lstart.',80');
		
		$userNum = $new['my']->findCount('user_follow',array(
			'userid'=>$strUser['userid'],
		));
		$pageUrl = pagination($userNum, 80, $page, $url);


		foreach($arrUsers as $item){
			$arrUser[] =  aac('user')->getOneUser($item['userid_follow']);
		}
		
		
		$title = '我关注的人';
		include template('friend_follow');
		break;
		
	//我的粉丝
	case "fans":
	
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = tsUrl('my','friend',array('ts'=>'fans','page'=>''));
		$lstart = $page*80-80;

		//跟随他的用户
		$arrUsers = $new['my']->findAll('user_follow',array(
			'userid_follow'=>$strUser['userid'],
		),'addtime desc',null,$lstart.',80');

		$userNum = $new['my']->findCount('user_follow',array(
			'userid_follow'=>$strUser['userid'],
		));
		$pageUrl = pagination($userNum, 80, $page, $url);


		foreach($arrUsers as $item){
			$arrUser[] =  aac('user')->getOneUser($item['userid']);
		}
		
	
		$title = '我的粉丝';
		include template('friend_fans');
		break;
	
}