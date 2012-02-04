<?php 
//移动帖子到其他小组
$userid = intval($TS_USER['user']['userid']);
$topicid = intval($_GET['topicid']);
$groupid = intval($_GET['groupid']);

if($groupid == 0 || $topicid == 0) tsNotice("非法操作！");

$strGroup = $db->once_fetch_assoc("select groupid,userid from ".dbprefix."discuss where groupid='$groupid'");

$strTopic = $db->once_fetch_assoc("select topicid,userid,groupid,title from ".dbprefix."discuss_topics where topicid='$topicid'");


if($userid == $strTopic['userid'] || $TS_USER['user']['isadmin']=='1' || $userid==$strGroup['userid']){

	if($strGroup && $strTopic){
		if($strTopic['groupid'] == $groupid){
			$arrGroups = $db->fetch_all_assoc("select groupid from ".dbprefix."discuss_users where userid='".$strTopic['userid']."'");
			foreach($arrGroups as $item){
				if($item['groupid'] != $groupid){
					$arrGroup[] = aac('group')->getOneGroup($item['groupid']);
				}
			}
			
			$title = L::topic_move_movetopic;
			//包含模板 
			include template("topic_move");
		}
	}else{
		tsNotice("非法操作3！");
	}

}else{
	tsNotice("非法操作2！");
}

