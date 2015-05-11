<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

	//小组列表
	case "list":
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=group&ac=admin&mg=group&ts=list&page=';
		$lstart = $page*10-10;
		$arrGroup = $db->fetch_all_assoc("select * from ".dbprefix."group order by addtime desc limit $lstart,10");
		$groupNum = $db->once_num_rows("select * from ".dbprefix."group");
		if(is_array($arrGroup)){
			foreach($arrGroup as $key=>$item){
				$arrAllGroup[] = $item;
				$arrAllGroup[$key]['groupdesc'] = cututf8($item['groupdesc'],0,40);
			}
		}
		$pageUrl = pagination($groupNum, 10, $page, $url);

		include template("admin/group_list");
		
		break;
	
	//小组添加
	case "add":
		include template("admin/group_add");
		break;
	
	//小组添加执行
	case "add_do":
		$userid = intval($_POST['userid']);
		$strUser = aac('user')->getOneUser($userid);
		
		$groupid = $new['group']->create('group',array(
			'userid' => $userid,
			'groupname'	=> t($_POST['groupname']),
			'groupdesc'	=> tsClean($_POST['groupdesc']),
			'isrecommend'	=> intval($_POST['isrecommend']),
			'addtime'	=> time(),
			'ispost'	=> intval($_POST['ispost']),
		));
		
		//更新group_user索引关系
		$groupUserNum = $db->once_num_rows("select * from ".dbprefix."group_user where userid='$userid' and groupid='$groupid'");
		if($groupUserNum > 0){
		}else{
			//插入小组成员索引
			$new['group']->create('group_user',array(
				'userid'=>$userid,
				'groupid'=>$groupid,
			));
			
			//计算小组会员数
			$groupUserNum = $new['group']->findCount('group_user',array(
				'groupid'=>$groupid,
			));
			
			//更新小组成员统计
			$new['group']->update('group',array(
				'groupid'=>$groupid,
			),array(
				'count_user'=>$groupUserNum,
			));
			
		}
		//回到小组管理首页
		header("Location: ".SITE_URL."index.php?app=group&ac=admin&mg=group&ts=list");
		break;
	
	//小组编辑
	case "edit":
		$groupid = intval($_GET['groupid']);
		$arrGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where groupid='$groupid'");
		include template("admin/group_edit");
		break;
	
	//小组编辑执行
	case "edit_do":
		$groupid = intval($_POST['groupid']);
		
		$new['group']->update('group',array(
			'groupid'=>$groupid,
		),array(
			'groupname'		=> t($_POST['groupname']),
			'groupdesc'		=> tsClean($_POST['groupdesc']),
			'userid'			=> intval($_POST['userid']),
			'ispost'	=> intval($_POST['ispost']),
		));
		
		qiMsg("小组信息修改成功！");
		break;
	
	//小组删除
	case "del":
		$groupid = intval($_GET['groupid']);
		
		if($groupid == 1){
			qiMsg("默认小组不能删除！");
		}
		
		$topicNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."group_topic where `groupid`='$groupid'");
		
		if($topicNum['count(*)'] > 0){
			qiMsg("本小组还有帖子，不允许删除。");
		}
		
		$db->query("DELETE FROM ".dbprefix."group WHERE groupid = '$groupid'");
		
		$db->query("DELETE FROM ".dbprefix."group_user WHERE groupid = '$groupid'");
		
		qiMsg("小组删除成功！");
		
		break;
		
	//审核小组 
	case "isaudit":
		$groupid = intval($_GET['groupid']);
		
		$db->query("update ".dbprefix."group set `isaudit`='0' where groupid='$groupid'");
		
		$strGroup = $db->once_fetch_assoc("select groupid,userid,groupname from ".dbprefix."group where groupid='$groupid'");
		
		//发送系统消息(审核通过)
		$msg_userid = '0';
		$msg_touserid = $strGroup['userid'];
		$msg_content = '恭喜你，你申请的小组《'.$strGroup['groupname'].'》审核通过！快去看看吧<br />'.tsUrl('group','show',array('id'=>$groupid));
		aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		
		qiMsg("小组审核通过！");
		
		break;
	
	//推荐小组 
	case "isrecommend":
		$groupid = intval($_GET['groupid']);
		
		$strGroup = $db->once_fetch_assoc("select groupid,userid,groupname,isrecommend from ".dbprefix."group where groupid='$groupid'");
		
		if($strGroup['isrecommend'] == 0){
			$db->query("update ".dbprefix."group set `isrecommend`='1' where groupid='$groupid'");
			
			//发送系统消息(审核通过)
			$msg_userid = '0';
			$msg_touserid = $strGroup['userid'];
			$msg_content = '恭喜你，你的小组《'.$strGroup['groupname'].'》被推荐啦！快去看看吧<br />'.tsUrl('group','show',array('id'=>$groupid));
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
			
		}else{
			
			$db->query("update ".dbprefix."group set `isrecommend`='0' where groupid='$groupid'");
			
		}
		
		qiMsg("操作成功！");
		
		break;
}