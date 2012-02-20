<?php 
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

switch($ts){
	case "":
		/*
		 1、Email验证  500积分
		 2、上传头像    500积分
		 3、每天发布一个帖子 20积分
		 4、每天回复5个帖子  20积分
		 5、邀请好友并通过Email验证 送1000积分 并送1TB=1RMB
		*/

		//是否Email验证
		$strUser = $db->once_fetch_assoc("select face,isverify from ".dbprefix."user_info where userid='$userid'");

		//今天是否发布帖子
		$starttime = strtotime(date('Y-m-d',time()));
		$strTopic = $db->once_fetch_assoc("select count(*) as ct from ".dbprefix."group_topics where userid='$userid' and `addtime`> '$starttime'");

		//今天是否有回帖子
		$strReply = $db->once_fetch_assoc("select count(*) as cr from ".dbprefix."group_topics_comments where userid='$userid' and `addtime`> '$starttime'");

		$title = '社区任务';

		include template("task");
		break;
	
	//检测是否有任务
	case "istask":

		//是否Email验证
		$strUser = $db->once_fetch_assoc("select face,isverify from ".dbprefix."user_info where userid='$userid'");

		//今天是否发布帖子
		$starttime = strtotime(date('Y-m-d',time()));
		$strTopic = $db->once_fetch_assoc("select count(*) as ct from ".dbprefix."group_topics where userid='$userid' and `addtime`> '$starttime'");

		//今天是否有回帖子
		$strReply = $db->once_fetch_assoc("select count(*) as cr from ".dbprefix."group_topics_comments where userid='$userid' and `addtime`> '$starttime'");
		
		if($strUser['face']=='' || $strUser['isverify']==0 || $strTopic['ct'] == 0 || $strReply['cr']==0){
			echo '<div style=" overflow: hidden;padding: 5px 0;">
					<span style="background:#FFFFCC;border:solid 1px #FFCC99;padding:2px 10px;">
					<a href="'.SITE_URL.tsUrl('group','task').'">亲，你有新任务啦！快去看看吧！</a>
					</span>
					</div>
					<br />';
		}
		
		break;
}