<?php
$userid = intval($_GET['userid']);
aac('user')->isUser($userid);

switch($ts){
	//group
	case "group":
		
		$arrGroups = $db->fetch_all_assoc("select groupid from ".dbprefix."group where userid='$userid'");
		foreach($arrGroups as $key=>$item){
			$arrGroup[] = aac('group')->getOneGroup($item['groupid']);
		}
		
		break;
		
	//topic
	case "topic":
		break;
}