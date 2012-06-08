<?php 
defined('IN_TS') or die('Access Denied.');
// 插件编辑
switch ($ts)
{
	case "show":
	$topicid=intval($_POST['topicid']);
    $user_list = $db->fetch_all_assoc("select m.*,mf.* from ".dbprefix."group_topics_collects m LEFT JOIN ".dbprefix."user_info mf on mf.userid=m.userid where m.topicid='$topicid' order by m.addtime DESC limit 20");
	foreach($user_list as $key=>$val){
	$list[] =  $val;
    }
	include template("userlist",'loveTopic');
	break;
}
