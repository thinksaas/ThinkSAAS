<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 小组管理
 */	

switch($ts){

	//小组列表
	case "list":
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = 'index.php?app=group&ac=admin&mg=group&ts=list&page=';
		$lstart = $page*10-10;
		$arrGroup = $db->fetch_all_assoc("select * from ".dbprefix."discuss order by addtime desc limit $lstart,10");
		$groupNum = $db->once_num_rows("select * from ".dbprefix."discuss");
		if(is_array($arrGroup)){
			foreach($arrGroup as $key=>$item){
				$arrAllGroup[] = $item;
				$arrAllGroup[$key]['groupdesc'] = getsubstrutf8($item['groupdesc'],0,40);
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
		$userid = trim($_POST['userid']);
		$strUser = $db->once_fetch_assoc("select * from ".dbprefix."user_info where userid='$userid'");
		$arrData = array(
			'userid' => $userid,
			'groupname'	=> trim($_POST['groupname']),
			'groupdesc'	=> trim($_POST['groupdesc']),
			'addtime'	=> time(),
			'ispost'	=> $_POST['ispost'],
		);
		
		$groupid = $db->insertArr($arrData,dbprefix.'group');
		
		//上传头像
		if (!empty($_FILES)) {
			$uptypes = array('jpg','gif','png');
			$dest_dir='uploadfile/group';
			createFolders($dest_dir);
			$arrType = explode('.',$_FILES['photo']['name']);
			$phototype = array_pop($arrType);
			if (in_array($phototype,$uptypes)) {
				$fileInfo=pathinfo($_FILES['photo']['name']);
				$extension=$fileInfo['extension'];
				$photoname = $groupid.'.'.$extension;
				$dest=$dest_dir.'/'.$photoname;
				move_uploaded_file($_FILES['photo']['tmp_name'],mb_convert_encoding($dest,"gb2312","UTF-8"));
				chmod($dest, 0755);
			}
		}
		
		//回到小组管理首页
		header("Location: ".SITE_URL."index.php?app=group&ac=admin&mg=group&ts=list");
		break;
	
	//小组编辑
	case "edit":
		$groupid = $_GET['groupid'];
		$arrGroup = $db->once_fetch_assoc("select * from ".dbprefix."discuss where groupid='$groupid'");
		include template("admin/group_edit");
		break;
	
	//小组编辑执行
	case "edit_do":
		$groupid = $_POST['groupid'];
		$arrData = array(
			'groupname'		=> $_POST['groupname'],
			'groupdesc'		=> $_POST['groupdesc'],
			'userid'			=> $_POST['userid'],
			'ispost'	=> $_POST['ispost'],
		);
		$db->updateArr($arrData,dbprefix.'group','where groupid='.$groupid.'');
		qiMsg("小组信息修改成功！");
		break;
	
	//小组删除
	case "del":
		$groupid = intval($_GET['groupid']);
		
		if($groupid == 1){
			qiMsg("默认小组不能删除！");
		}
		
		$topicNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."discuss_topics where `groupid`='$groupid'");
		
		if($topicNum['count(*)'] > 0){
			qiMsg("本小组还有帖子，不允许删除。");
		}
		
		$db->query("DELETE FROM ".dbprefix."discuss WHERE groupid = '$groupid'");
		$db->query("DELETE FROM ".dbprefix."discuss_cates_index WHERE groupid = '$groupid'");
		$db->query("DELETE FROM ".dbprefix."discuss_users WHERE groupid = '$groupid'");
		
		qiMsg("小组删除成功！");
		
		break;
	
	//推荐小组 
	case "isrecommend":
		$groupid = $_GET['groupid'];
		
		$strGroup = $db->once_fetch_assoc("select groupid,userid,groupname,isrecommend from ".dbprefix."discuss where groupid='$groupid'");
		
		if($strGroup['isrecommend'] == 0){
			$db->query("update ".dbprefix."discuss set `isrecommend`='1' where groupid='$groupid'");
			
			//发送系统消息(审核通过)
			$msg_userid = '0';
			$msg_touserid = $strGroup['userid'];
			$msg_content = '恭喜你，你的小组《'.$strGroup['groupname'].'》被推荐啦！快去看看吧<br />'.SITE_URL.'index.php?app=group&ac=group&groupid='.$groupid;
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
			
		}else{
			
			$db->query("update ".dbprefix."discuss set `isrecommend`='0' where groupid='$groupid'");
			
		}
		
		qiMsg("操作成功！");
		
		break;
}