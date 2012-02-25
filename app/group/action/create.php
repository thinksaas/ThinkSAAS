<?php
//创建小组
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();


switch($ts){
	
	case "":
	
		$arrOne = $db->fetch_all_assoc("select * from ".dbprefix."group_cates where catereferid='0'");

		$title = '创建小组';

		include template("create");
	
		break;
	
	
	//执行创建小组
	case "do":
	
		$oneid = intval($_POST['oneid']);
		$twoid = intval($_POST['twoid']);
		$threeid = intval($_POST['threeid']);
		
		if($oneid != 0 && $twoid==0 && $threeid==0){
			$cateid = $oneid;
		}elseif($oneid!=0 && $twoid !=0 && $threeid==0){
			$cateid = $twoid;
		}elseif($oneid!=0 && $twoid !=0 && $threeid!=0){
			$cateid = $threeid;
		}else{
			$cateid = 0;
		}
		
		if($userid=='0' || $_POST['groupname']=='' || $_POST['groupdesc']=='') tsNotice("色即是空，空即是色！");
		
		//配置文件是否需要审核
		$isaudit = intval($TS_APP['options']['isaudit']);
		
		$groupname = h($_POST['groupname']);
		
		$isGroup = $db->once_fetch_assoc("select count(groupid) from ".dbprefix."group where groupname='$groupname'");
		
		if($isGroup['count(groupid)'] > 0) tsNotice("小组名称已经存在，请更换其他小组名称！");
		
		$arrData = array(
			'userid'			=> $userid,
			'groupname'	=> $groupname,
			'groupdesc'		=> $_POST['groupdesc'],
			'isaudit'	=> $isaudit,
			'addtime'		=> time(),
		);
		
		$groupid = $db->insertArr($arrData,dbprefix.'group');
		$uptime = time();
		
		//绑定小组分类开始
		if($cateid > 0){
			$db->query("INSERT INTO ".dbprefix."group_cates_index (`groupid`,`cateid`) VALUES ('$groupid','$cateid')");
			//更新分类下小组数
			$groupnum = $db->once_num_rows("select * from ".dbprefix."group_cates_index where cateid='$cateid'");
			
			$db->query("update ".dbprefix."group_cates set `count_group`='$groupnum',`uptime`='$uptime' where cateid='$cateid'");
			
			
			//判断是否有顶级分类
			$strCate = $db->once_fetch_assoc("select catereferid from ".dbprefix."group_cates where cateid='$cateid'");
			$catereferid = $strCate['catereferid'];
			
			if($catereferid > 0){
				//统计顶级分类下小组数
				$grouptotal = $db->once_fetch_assoc("select sum(`count_group`) as `total` from ".dbprefix."group_cates where catereferid='$catereferid'");
				
				$total = $grouptotal['total'];
				
				$db->query("update ".dbprefix."group_cates set `count_group`='$total',`uptime`='$uptime' where cateid='$catereferid'");
			}
		}
		//绑定小组分类结束
		
		//绑定成员
		$db->query("insert into ".dbprefix."group_users (`userid`,`groupid`,`addtime`) values ('".$userid."','".$groupid."','".time()."')");
		
		//更新
		$db->query("update ".dbprefix."group set `count_user` = '1' where groupid='".$groupid."'");

		header("Location: ".SITE_URL.tsUrl('group','show',array('id'=>$groupid)));
	
		break;
	
}