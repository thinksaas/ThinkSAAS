<?php
//创建小组
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();


switch($ts){
	
	case "":
	
		//先判断加入多少个小组啦 
		$userGroupNum = $new['group']->findCount('group_users',array('userid'=>$userid));
		
		if($userGroupNum >= $TS_APP['options']['joinnum']) tsNotice('你加入的小组总数已经到达'.$TS_APP['options']['joinnum'].'个，不能再创建小组！');
		
		if($TS_APP['options']['iscreate'] == 0 || $TS_USER['user']['isadmin']==1){
		
			//小组分类
			$arrCate = $new['group']->findAll('group_cates');
		
			$title = '创建小组';

			include template("create");
		
		}else{
		
			tsNotice('系统不允许会员创建小组！');
		
		}
	

	
		break;
	
	
	//执行创建小组
	case "do":
	
		if($TS_APP['options']['iscreate'] == 0 || $TS_USER['user']['isadmin']==1){
	
			if($_POST['grp_agreement'] != 1) tsNotice('不同意社区指导原则是不允许创建小组的！');
			
			if($_POST['groupname']=='' || $_POST['groupdesc']=='') tsNotice('小组名称和介绍不能为空！');
			
			//配置文件是否需要审核
			$isaudit = intval($TS_APP['options']['isaudit']);
			
			$groupname = trim($_POST['groupname']);
			
			$isGroup = $db->once_fetch_assoc("select count(groupid) from ".dbprefix."group where groupname='$groupname'");
			
			if($isGroup['count(groupid)'] > 0) tsNotice("小组名称已经存在，请更换其他小组名称！");
			
			$arrData = array(
				'userid'			=> $userid,
				'cateid'=>intval($_POST['cateid']),
				'groupname'	=> $groupname,
				'groupdesc'		=> trim($_POST['groupdesc']),
				'isaudit'	=> $isaudit,
				'addtime'		=> time(),
			);
			
			$groupid = $db->insertArr($arrData,dbprefix.'group');

			//上传
			$arrUpload = tsUpload($_FILES['picfile'],$groupid,'group',array('jpg','gif','png'));
			
			if($arrUpload){

				$new['group']->update('group',array(
					'groupid'=>$groupid,
				),array(
					'path'=>$arrUpload['path'],
					'groupicon'=>$arrUpload['url'],
				));
			}
			
			//绑定成员
			$db->query("insert into ".dbprefix."group_users (`userid`,`groupid`,`addtime`) values ('".$userid."','".$groupid."','".time()."')");
			
			//更新
			$db->query("update ".dbprefix."group set `count_user` = '1' where groupid='".$groupid."'");
			
			//更新分类统计
			$cateid = intval($_POST['cateid']);
			if($cateid > 0){
				$count_group = $new['group']->findCount('group',array(
					'cateid'=>$cateid,
				));
				
				$new['group']->update('group_cates',array(
					'cateid'=>$cateid,
				),array(
					'count_group'=>$count_group,
				));
		
			}

			header("Location: ".SITE_URL.tsUrl('group','show',array('id'=>$groupid)));
		}
		break;
	
}