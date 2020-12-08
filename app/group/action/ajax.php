<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
		
	//加入小组
	case "joingroup":
		
		$userid = tsIntval($TS_USER['userid']);
	
		$groupid = tsIntval($_POST['groupid']);
		
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



            #付费加入小组
            if($TS_APP['ispayjoin']==1 && $strGroup['joinway']==3){
                //启动支付帐号
                $strUserPay = aac('pay')->getUserPay($userid);
                if($strUserPay['over']<$strGroup['price']){
                    getJson('支付帐号资金不足，请充值后再加入小组！');
                }
                //用户加入付款消费
                aac('pay')->updatePay($userid,$strGroup['price'],1,'加入收费小组'.$strGroup['groupid']);

                #组长获取加入收费收入
                aac('pay')->updatePay($strGroup['userid'],$strGroup['price'],0,'收费小组获取'.$strGroup['groupid']);
            }


		
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
		
		getJson('加入成功！',1,1,tsUrl('group','show',array('id'=>$groupid)));
		
		break;
		
	//退出小组
	case "exitgroup":
	
		$userid = tsIntval($TS_USER['userid']);
		
		$groupid = tsIntval($_POST['groupid']);
		
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

        getJson('加入成功！',1,1,tsUrl('group','show',array('id'=>$groupid)));

		break;
		
	

}