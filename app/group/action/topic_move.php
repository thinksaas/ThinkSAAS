<?php 
//移动帖子到其他小组
$userid = intval($TS_USER['user']['userid']);
$topicid = intval($_GET['topicid']);
$groupid = intval($_GET['groupid']);

if($groupid == 0 || $topicid == 0) qiMsg("非法操作！");

$strGroup = $db->once_fetch_assoc("select groupid,userid from ".dbprefix."group where groupid='$groupid'");

$strTopic = $db->once_fetch_assoc("select topicid,userid,groupid,title from ".dbprefix."group_topics where topicid='$topicid'");

$strUser = $db->once_fetch_assoc("select userid,isadmin from ".dbprefix."user_info where userid='$userid'");


if($userid == $strTopic['userid'] || $strUser['isadmin']=='1' || $userid==$strGroup['userid']){

	if($strGroup && $strTopic){
		if($strTopic['groupid'] == $groupid){
			$arrGroups = $db->fetch_all_assoc("select groupid from ".dbprefix."group_users where userid='".$strTopic['userid']."'");
			foreach($arrGroups as $item){
				$arrGroup[] = aac('group')->getOneGroup($item['groupid']);
			}
			
			$title = '移动帖子';
			//包含模板 
			include template("topic_move");
		}
	}else{
		qiMsg("非法操作3！");
	}

}else{
	qiMsg("非法操作2！");
}

