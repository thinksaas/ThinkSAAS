<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	//帖子审核
	case "topicaudit":
	
		$topicid = intval($_POST['topicid']);
		
		$token = trim($_POST['token']);
		
	
		if($TS_USER['isadmin']==0 || $token!=$_SESSION['token']){
			echo 2;exit;//非法操作
		}
		
		
		
		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$topicid,
		));
		
		if($strTopic['isaudit']==0){
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>1,
			));
			
			echo 0;exit;
			
		}
		
		if($strTopic['isaudit']==1){
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>0,
			));
			
			echo 1;exit;
			
		}
		
		break;
		
	//加入小组
	case "joingroup":
		
		$userid = intval($TS_USER['userid']);
	
		$groupid = intval($_POST['groupid']);
		
		$strGroup = $new['group']->find('group',array(
			'groupid'=>$groupid
		));
		
		if($userid==0 || $groupid==0 || $strGroup==''){
			getJson('请登录后再加入小组',1,2,tsUrl('user','login'));
		}
		
		//管理员可以加入任何小组
		if($TS_USER['isadmin'] != 1){
			
			//除管理员外其他用户都要经过这一关审核
			if($strGroup['joinway'] == 1) getJson('本小组禁止加入！');
			
			//处理申请加入,成员审核
			if($strGroup['joinway'] == 2){
				$new['group']->replace('group_user_isaudit',array(
					'userid'=>$userid,
					'groupid'=>$strGroup['groupid'],
				),array(
					'userid'=>$userid,
					'groupid'=>$strGroup['groupid'],
				));
				
				getJson('加入小组申请提交成功，请等待管理员审核后加入。');
				
			}
			
			//先统计用户有多少个小组了，50个封顶
			$userGroupNum = $new['group']->findCount('group_user',array('userid'=>$userid));
			
			if($userGroupNum >= $TS_APP['joinnum']) getJson('你加入的小组总数已经到达'.$TS_APP['joinnum'].'个，不能再加入小组！');
			
			$groupUserNum = $new['group']->findCount('group_user',array(
				'userid'=>$userid,
				'groupid'=>$groupid,
			));
			
			if($groupUserNum > 0) getJson('你已经加入小组！');
		
		}
		
		$new['group']->create('group_user',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
			'addtime'=>time(),
		));
		
		//更新
		$count_group = $new['group']->findCount('group_user',array(
			'userid'=>$userid,
		));
		$new['group']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'count_group'=>$count_group,
		));
		
		//计算小组会员数
		$count_user = $new['group']->findCount('group_user',array(
			'groupid'=>$groupid,
		));
		
		//更新小组成员统计
		$new['group']->update('group',array(
			'groupid'=>$groupid,
		),array(
			'count_user'=>$count_user,
		));
		
		getJson('加入成功！',1,1);
		
		break;
		
	//退出小组
	case "exitgroup":
	
		$userid = intval($TS_USER['userid']);
		
		$groupid = intval($_POST['groupid']);
		
		//判断是否是组长，是组长不能退出小组
		$strGroup = $new['group']->find('group',array(
			'groupid'=>$groupid
		));
		
		if($userid==0 || $groupid==0 || $strGroup==''){
			getJson('非法操作');
		}
		
		if($strGroup['userid'] == $userid) getJson('组长任务艰巨，请坚持到底！');
		
		$new['group']->delete('group_user',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
		));
		
		//计算小组会员数
		$count_user = $new['group']->findCount('group_user',array(
			'groupid'=>$groupid,
		));
		
		//更新小组成员统计
		$new['group']->update('group',array(
			'groupid'=>$groupid,
		),array(
			'count_user'=>$count_user,
		));
	
		getJson('退出成功！',1,1);

		break;
		
	//帖子推荐
	case "isrecommend":
	
		$js = intval($_GET['js']);
		
		$topicid = intval($_POST['topicid']);
		
		if($TS_USER['isadmin']==1 && $topicid){
		
			$strTopic = $new['group']->find('group_topic',array(
				'topicid'=>$topicid,
			));
			
			if($strTopic['isrecommend']==1){
				$new['group']->update('group_topic',array(
					'topicid'=>$topicid,
				),array(
					'isrecommend'=>0,
				));
				
				getJson('取消推荐成功！',$js);
				
			}
			
			if($strTopic['isrecommend']==0){
				$new['group']->update('group_topic',array(
					'topicid'=>$topicid,
				),array(
					'isrecommend'=>1,
				));
				
				getJson('推荐成功！',$js);
				
			}
			
		
		}else{
		
			getJson('非法操作',$js);
		
		}
		
		break;
}