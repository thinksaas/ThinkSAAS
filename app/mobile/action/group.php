<?php
defined('IN_TS') or die('Access Denied.');


switch($ts){

	case "":
	
		$arrGroup = $new['mobile']->findAll('group',array(
			'isaudit'=>'0',
		),'isrecommend desc,addtime asc',null,10);

		$title = '小组'.' - '.$TS_CF['info']['powered'];
		include template('group');
	
		break;
		
	case "show":
	
		$groupid = intval($_GET['groupid']);
		$strGroup = aac('group')->getOneGroup($groupid);
		
		//判断用户是否加入
		$strGroup['isjoin']=0;
		$userid = intval($TS_USER['user']['userid']);
		
		if($userid){
			$isGroupUser = $new['mobile']->findCount('group_user',array(
				'userid'=>$userid,
				'groupid'=>$strGroup['groupid'],
			));
			
			if($isGroupUser>0){
				$strGroup['isjoin']=1;
			}
			
			if($strGroup['userid']==$userid){
				$strGroup['isjoin']=1;
			}
		}
		
		//最新帖子	
		$arrNewTopics = aac('group')->findAll('group_topic',"`groupid`='$groupid' and `isaudit`='0'",'uptime desc',null,35);
		foreach($arrNewTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['title']=htmlspecialchars($item['title']);
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
		}
		
		
		$arrHeadButton = array(
			'left'=>array(
				'title'=>'返回',
				'url'=>tsUrl('mobile','group'),
			),
			'right'=>array(
				'title'=>'发帖',
				'url'=>tsUrl('mobile','topic',array('ts'=>'add','groupid'=>$strGroup['groupid'])),
			),
		);
		
		$title = $strGroup['groupname'].' - '.$TS_CF['info']['powered'];
		include template('group_show');
		break;
		
	//加入小组
	case "join":
		
		$userid = intval($TS_USER['user']['userid']);
		$groupid = intval($_POST['groupid']);
		
		if($userid==0){
			echo 0;exit;//请登录后再加入小组
		}
		
		$isGroupUser = $new['mobile']->findCount('group_user',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
		));
		
		if($isGroupUser>0){
			echo 1;exit;//你已经加入该小组
		}
		
		$new['mobile']->create('group_user',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
			'addtime'=>time(),
		));
		
		echo 2;exit;//加入小组成功！
		
		break;

}