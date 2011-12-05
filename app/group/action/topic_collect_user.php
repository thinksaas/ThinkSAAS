<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 谁收藏了这篇帖子
 */

$topicid = intval($_GET['topicid']);

switch($ts){
	case "ajax":
		
		$arrCollectUser = $db->fetch_all_assoc("select * from ".dbprefix."group_topics_collects where topicid='$topicid'");
		
		if(is_array($arrCollectUser)){
			foreach($arrCollectUser as $item){
				$strUser = aac('user')->getUserForApp($item['userid']);
				$arrUser[] = $strUser;
			}
		}
		
		if($arrUser == ''){
			echo '<div style="color: #999999;margin-bottom: 10px;padding: 20px 0">还没有人收藏，赶快来做第一个收藏者吧^_^</div>';
		}else{
			include template("topic_collect_user_ajax");
		}
		
		break;
}