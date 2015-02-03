<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 谁收藏了这篇帖子
 */

$topicid = intval($_GET['topicid']);

switch($ts){
	case "ajax":
		
		$arrCollectUser = $db->fetch_all_assoc("select * from ".dbprefix."group_topic_collect where topicid='$topicid'");
		
		if(is_array($arrCollectUser)){
			foreach($arrCollectUser as $item){
				$strUser = aac('user')->getOneUser($item['userid']);
				$arrUser[] = $strUser;
			}
		}
		
		if($arrUser == ''){
			echo '<div style="color: #999999;margin-bottom: 10px;padding: 20px 0">还没有人喜欢，赶快来做第一个喜欢者吧^_^</div>';
		}else{
			include template("topic_collect");
		}
		
		break;
}