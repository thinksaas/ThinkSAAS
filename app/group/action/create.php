<?php
//创建小组
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();


switch($ts){
	
	case "":
	
		//先判断加入多少个小组啦 
		$userGroupNum = $new['group']->findCount('group_user',array(
			'userid'=>$userid
		));
		
		if($userGroupNum >= $TS_APP['joinnum'] && $TS_USER['isadmin']==0){
		
			tsNotice('你加入的小组总数已经到达'.$TS_APP['joinnum'].'个，不能再创建小组！');
		
		}
		
		if($TS_APP['iscreate'] == 0 || $TS_USER['isadmin']==1){
		
			//小组分类
			$arrCate = $new['group']->findAll('group_cate',array(
			
				'referid'=>0,
			
			));
		
			$title = '创建小组';

			include template("create");
		
		}else{
		
			tsNotice('系统不允许会员创建小组！');
		
		}
		break;
	
	//执行创建小组
	case "do":
	
		if($TS_APP['iscreate'] == 0 || $TS_USER['isadmin']==1){
			
			$groupname = trim($_POST['groupname']);
			$groupdesc = tsClean($_POST['groupdesc']);
			
			if($groupname=='' || $groupdesc=='') {
				tsNotice('小组名称和介绍不能为空！');
			}
			
			//过滤内容开始
			if($TS_USER['isadmin']!=1){
				aac('system')->antiWord($groupname);
				aac('system')->antiWord($groupdesc);
			}
			//过滤内容结束
			
			//配置文件是否需要审核
			$isaudit = intval($TS_APP['isaudit']);
			if($TS_USER['isadmin']==1){
				$isaudit = 0;
			}
			
			$isGroup = $new['group']->findCount('group',array(
				'groupname'=>$groupname,
			));
			
			if($isGroup > 0) {
				tsNotice("小组名称已经存在，请更换其他小组名称！");
			}

			$groupid = $new['group']->create('group',array(
				'userid'	=> $userid,
				'groupname'	=> $groupname,
				'groupdesc'	=> $groupdesc,
				'isaudit'	=> $isaudit,
				'addtime'	=> time(),
			));

			//上传
			$arrUpload = tsUpload($_FILES['photo'],$groupid,'group',array('jpg','gif','png','jpeg'));
			
			if($arrUpload){

				$new['group']->update('group',array(
					'groupid'=>$groupid,
				),array(
					'path'=>$arrUpload['path'],
					'photo'=>$arrUpload['url'],
				));
			}
			
			//绑定成员
			$new['group']->create('group_user',array(
				'userid'=>$userid,
				'groupid'=>$groupid,
				'addtime'=>time(),
			));
			
			//更新
			$count_group = $new['group']->findCount('group_user',array(
				'userid'=>$userid,
			));
			$new['group']->update('user_info',array(
				'userid'=>$userid,
			),array(
				'count_group'=>$count_group,
			));
			
			//更新小组人数
			$new['group']->update('group',array(
				'groupid'=>$groupid,
			),array(
				'count_user'=>1,
			));
			
			//更新分类统计
			$cateid = intval($_POST['cateid']);
			if($cateid > 0){
				$count_group = $new['group']->findCount('group',array(
					'cateid'=>$cateid,
				));
				
				$new['group']->update('group_cate',array(
					'cateid'=>$cateid,
				),array(
					'count_group'=>$count_group,
				));
		
			}
			
			// 处理标签
			aac ( 'tag' )->addTag ( 'group', 'groupid', $groupid, $_POST['tag'] );

			header("Location: ".tsUrl('group','show',array('id'=>$groupid)));
		}
		break;
	
}