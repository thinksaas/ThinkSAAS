<?php
defined('IN_TS') or die('Access Denied.');

$groupid = intval($_GET['id']);
if($groupid == '0') header("Location: ".SITE_URL."index.php");
$strGroup = $new['group']->getOneGroup($groupid);
$strGroup['groupdesc'] = stripslashes($strGroup['groupdesc']);

switch($ts){
	//小组
	case "":
		$typeid = intval($_GET['typeid']);

		$strGroup['recoverynum'] = $db->once_num_rows("select * from ".dbprefix."group_topics where groupid='$groupid' and isshow='1'");

		$strGroup['groupdesc'] = editor2html($strGroup['groupdesc']);

		$title = $strGroup['groupname'];

		//小组帖子分类
		$arrTopicTypes = $db->fetch_all_assoc("select * from ".dbprefix."group_topics_type where groupid='$groupid'");
		if(is_array($arrTopicTypes)){
			foreach($arrTopicTypes as $item){
				$arrTopicType[$item['typeid']] = $item;
			}
		}

		//组长信息
		$leaderId = $strGroup['userid'];

		$strLeader = $db->once_fetch_assoc("select * from ".dbprefix."user_info where userid='$leaderId'");

		//判断会员是否加入该小组
		$userid = $TS_USER['user']['userid'];

		$isGroupUser = $db->once_num_rows("select * from ".dbprefix."group_users where userid='$userid' and groupid='$groupid'");


		//小组是否需要审核
		if($strGroup['isaudit']=='1'){
			//推荐小组
			$arrRecommendGroup = $new['group']->getRecommendGroup('7');
			include template("group_isaudit");

			}elseif($strGroup['isopen']=='1' && $isGroupUser=='0'){
			//是否开放访问
				include template("group_isopen");
			}else{

			$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

			$lstart = $page*30-30;

			if($typeid == '0'){
				$andType = '';
				$url = SITE_URL.tsurl('group','show',array('id'=>$groupid,'page'=>''));
			}else{
				$andType = "and typeid='$typeid'";
				$url = SITE_URL.tsurl('group','show',array('id'=>$groupid,'typeid'=>$typeid,'page'=>''));
			}

			$sql = "select topicid,typeid,groupid,userid,title,count_comment,count_view,istop,isphoto,isattach,isposts,addtime,uptime from ".dbprefix."group_topics where groupid='$groupid' ".$andType." and isshow='0' order by istop desc,uptime desc limit $lstart,30";

			$arrTopics = $db->fetch_all_assoc($sql);
			if( is_array($arrTopics)){
				foreach($arrTopics as $key=>$item){
					$arrTopic[] = $item;
					$arrTopic[$key]['typename'] = $arrTopicType[$item['typeid']]['typename'];
					$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
					$arrTopic[$key]['group'] = aac('group')->getSimpleGroup($item['groupid']);
					$arrTopic[$key]['photo'] = $new['group']->getOnePhoto($item['topicid']);
				}
			}

			$topic_num = $db->once_fetch_assoc("select count(topicid) from ".dbprefix."group_topics where groupid='$groupid' ".$andType." and isshow='0'");

			$pageUrl = pagination($topic_num['count(topicid)'], 30, $page, $url);


			//小组会员
			$groupUser = $db->fetch_all_assoc("select userid from ".dbprefix."group_users where groupid='$groupid' order by addtime DESC limit 8");

			if(is_array($groupUser)){
				foreach($groupUser as $item){
					$arrGroupUser[] = aac('user')->getUserForApp($item['userid']);
				}
			}

			if($page > 1){
				$title = $strGroup['groupname'].' - 第'.$page.'页';
			}

			include template("group");

		}
	
		break;
		
	//小组成员
	case "user":

		//小组组长信息
		$leaderId = $strGroup['userid'];
		$strLeader = aac('user')->getUserForApp($leaderId);

		//管理员信息
		$strAdmin = $db->fetch_all_assoc("select userid from ".dbprefix."group_users where groupid='$groupid' and isadmin='1'");

		if(is_array($strAdmin)){
			foreach($strAdmin as $item){
				$arrAdmin[] = aac('user')->getUserForApp($item['userid']);
			}
		}

		//小组会员分页

		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

		$url = SITE_URL.tsurl('group','guser',array('groupid'=>$groupid,'page'=>''));


		$lstart = $page*40-40;

		$groupUserNum = $db->once_num_rows("select userid from ".dbprefix."group_users where groupid='$groupid'");

		$groupUser = $db->fetch_all_assoc("select userid,isadmin from ".dbprefix."group_users where groupid='$groupid' order by userid desc limit $lstart,40");

		if(is_array($groupUser)){
			foreach($groupUser as $key=>$item){
				$arrGroupUser[] = aac('user')->getUserForApp($item['userid']);
				$arrGroupUser[$key]['isadmin'] = $item['isadmin'];
			}
		}

		$pageUrl = pagination($groupUserNum, 40, $page, $url,$TS_URL['suffix']);

		if($page > '1'){
			$titlepage = " - 第".$page."页";
		}else{
			$titlepage='';
		}

		$title = $strGroup['groupname'].'小组成员'.$titlepage;

		include template("group_user");
		break;
}