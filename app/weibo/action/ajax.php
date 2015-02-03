<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
		
	case "add":
		
		$userid = intval($TS_USER['user']['userid']);

		if($userid==0){
			echo 0;exit;
		}

		$content = tsClean($_POST['content']);

		if($content == ''){
			echo 1;exit;
		}
		
		if($TS_USER['user']['isadmin']==0){
			//过滤内容开始
			aac('system')->antiWord($content);
			//过滤内容结束
			//$isaudit = 1;
		}
			
		$weiboid = $new['weibo']->create('weibo',array(
			'userid'=>$userid,
			'locationid'=>aac('user')->getLocationId($userid),
			'content'=>$content,
			'addtime'=>date('Y-m-d H:i:s'),
			'uptime'=>date('Y-m-d H:i:s'),
		));
		
		//对积分进行处理
		aac('user')->doScore($app,$ac,$ts);
		
		//QQ分享
		$arrShare = array(
			'content'=>$content.'[ThinkSAAS社区]'.tsurl('weibo','show',array('id'=>$weiboid)),
		);
		doAction('qq_share',$arrShare);
		//微博分享
		doAction('weibo_share',$content.'[ThinkSAAS社区]'.tsurl('weibo','show',array('id'=>$weiboid)));
		
		echo 2;exit;
		
		break;
		
	case "list":
	
		$arrWeibo = aac('weibo')->findAll('weibo',null,'addtime desc',null,10);
		foreach($arrWeibo as $key=>$item){
			$arrWeibo[$key]['content'] = tsDecode($item['content']);
			$arrWeibo[$key]['user'] = aac('user')->getOneUser($item['userid']);
		}
		
		include template('ajax_list');
	
		break;
	
}