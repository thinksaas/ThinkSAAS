<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "join":
		
		
		$userid = intval($TS_USER['userid']);
		
		$locationid = intval($_POST['locationid']);
		
		if($userid==0){
			getJson('请登录后再加入！');
		}
		
		//更新ts_article、ts_attach、ts_group_topic、ts_photo、ts_user_info、ts_weibo
		$new['location']->update('article',array(
			'userid'=>$userid,
		),array(
			'locationid'=>$locationid,
		));
		
		$new['location']->update('attach',array(
			'userid'=>$userid,
		),array(
			'locationid'=>$locationid,
		));
		
		$new['location']->update('group_topic',array(
			'userid'=>$userid,
		),array(
			'locationid'=>$locationid,
		));
		
		$new['location']->update('photo',array(
			'userid'=>$userid,
		),array(
			'locationid'=>$locationid,
		));
		
		$new['location']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'locationid'=>$locationid,
		));
		
		$new['location']->update('weibo',array(
			'userid'=>$userid,
		),array(
			'locationid'=>$locationid,
		));
		
		getJson('加入成功！',1,1);
		
		break;
	
	case "exit":
		
		$userid = intval($TS_USER['userid']);
		
		$locationid = intval($_POST['locationid']);
		
		if($userid==0 || $locationid == 0){
			getJson('非法操作！');
		}
		
		//更新ts_article、ts_attach、ts_group_topic、ts_photo、ts_user_info、ts_weibo
		$new['location']->update('article',array(
			'userid'=>$userid,
		),array(
			'locationid'=>0,
		));
		
		$new['location']->update('attach',array(
			'userid'=>$userid,
		),array(
			'locationid'=>0,
		));
		
		$new['location']->update('group_topic',array(
			'userid'=>$userid,
		),array(
			'locationid'=>0,
		));
		
		$new['location']->update('photo',array(
			'userid'=>$userid,
		),array(
			'locationid'=>0,
		));
		
		$new['location']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'locationid'=>0,
		));
		
		$new['location']->update('weibo',array(
			'userid'=>$userid,
		),array(
			'locationid'=>0,
		));
		
		getJson('退出成功！',1,2,tsUrl('location'));
		
		break;
	
}