<?php
defined('IN_TS') or die('Access Denied.');

$groupid = intval($_GET['groupid']);
$strGroup = $new['group']->getOneGroup($groupid);
$strGroup['groupdesc'] = stripslashes($strGroup['groupdesc']);

if(intval($TS_USER['user']['userid']) == $strGroup['userid'] || intval($TS_USER['user']['isadmin']==1)){

	switch($ts){
		//编辑小组基本信息
		case "base":
			
			$title = L::edit_editgroup;
			include template("edit_base");
			break;
		
		case "base_do":
			if($_POST['groupname']=='' || $_POST['groupdesc']=='') qiMsg("小组名称和介绍都不能为空！");
		
			$arrData = array(
				'groupname'	=> h($_POST['groupname']),
				'groupdesc'	=> trim($_POST['groupdesc']),
				'joinway'		=> intval($_POST['joinway']),
				'ispost'	=> intval($_POST['ispost']),
				'isopen'		=> intval($_POST['isopen']),
				'role_leader'	=> t($_POST['role_leader']),
				'role_admin'	=> t($_POST['role_admin']),
				'role_user'	=> t($_POST['role_user']),
			);
			
			$groupid = intval($_POST['groupid']);
			
			$db->updateArr($arrData,dbprefix.'group','where groupid='.$groupid.'');
			//更新所有帖子中对应小组的名称
			header("Location: ".SITE_URL."index.php?app=group&ac=edit&groupid=".$groupid."&ts=base");
			break;
		
		//编辑小组头像
		case "icon":
			$title = L::edit_editgroup;
			include template("edit_icon");
			break;
			
		case "icon_do":
			$groupid = intval($_POST['groupid']);
			//上传头像
			if (!empty($_FILES)) {
				$uptypes = array('jpg','gif','png');
				$menu2=intval($groupid/1000);
				$menu1=intval($menu2/1000);
				$menu = $menu1.'/'.$menu2;
				$dest_dir='uploadfile/group/'.$menu;
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
					$photo = $menu.'/'.$photoname;
					$db->query("update ".dbprefix."group set `path`='$menu',`groupicon`='$photo' where groupid='$groupid'");
				}
			}
			break;
		
		//小组分类
		case "cate":
			
			$arrCate = array();
			
			$groupcateindexnum = $db->once_num_rows("select * from ".dbprefix."group_cates_index where groupid='$groupid'");
			
			if($groupcateindexnum > 0){
				$arrGroupCateIndex = $db->fetch_all_assoc("select * from ".dbprefix."group_cates_index where groupid='$groupid'");
				
				
				foreach($arrGroupCateIndex as $key=>$item){
					$strCate = $db->once_fetch_assoc("select * from ".dbprefix."group_cates where cateid='".$item['cateid']."'");
					$arrCate[] = $strCate;
				}
			}
			
			$title = '编辑小组分类';
			include template("edit_cate");
			
			break;
			
		//帖子分类
		case "type":
			//调出类型
			$arrGroupType = $db->fetch_all_assoc("select * from ".dbprefix."group_topics_type where groupid='".$strGroup['groupid']."'");
			
			$title = '编辑帖子分类';
			include template("edit_type");
			break;
		
		case "type_do":
			
			$typeid = intval($_POST['typeid']);
			$typename = trim($_POST['typename']);
			$db->query("update ".dbprefix."group_topics_type set 	`typename`='$typename' where `typeid`='$typeid'");
			
			qiMsg("修改成功！");
			
			break;
			
		case "type_add":
			$groupid = intval($_POST['groupid']);
			$typename = t($_POST['typename']);
			if($typename != '')
			  $db->query("insert into ".dbprefix."group_topics_type (`groupid`,`typename`) values ('$groupid','$typename')");
			
			header("Location: ".SITE_URL.tsurl('group','edit',array('ts'=>'type','groupid'=>$groupid)));
			break;
		
		case "type_del":
			$typeid = intval($_GET['typeid']);
			
			$db->query("delete from ".dbprefix."group_topics_type where `typeid`='$typeid'");
			header("Location: ".SITE_URL.tsurl('group','edit',array('ts'=>'type','groupid'=>$groupid)));
			
			break;
		
	}

}else{
	header("Location: ".SITE_URL);
}