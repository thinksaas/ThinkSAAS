<?php 
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

switch ($ts) {
	
	//上传小组头像
	
	case "photo":
		
		$groupid = intval($_POST['groupid']);
		
		//上传
		$arrUpload = tsUpload($_FILES['picfile'],$groupid,'group',array('jpg','gif','png'));
		
		if($arrUpload){

			$new['group']->update('group',array(
				'groupid'=>$groupid,
			),array(
				'path'=>$arrUpload['path'],
				'photo'=>$arrUpload['url'],
			));
			
			tsDimg($arrUpload['url'],'group','48','48',$arrUpload['path']);
			tsDimg($arrUpload['url'],'group','16','16',$arrUpload['path']);
			tsDimg($arrUpload['url'],'group','32','32',$arrUpload['path']);
			tsDimg($arrUpload['url'],'group','24','24',$arrUpload['path']);
			tsDimg($arrUpload['url'],'group','120','120',$arrUpload['path']);
			
			tsNotice("小组图标修改成功！");
			
		}else{
			tsNotice("上传出问题啦！");
		}
		
		break;
	
	//编辑小组基本信息
	case "edit_base":
	
		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}
		$groupid = intval($_POST['groupid']);
		
		$strGroup = $new['group']->find('group',array(
			'groupid'=>$groupid,
		));
		
		if($strGroup['userid']==$userid || $TS_SITE['user']['isadmin']==1){
		
			$groupname = trim($_POST['groupname']);
			$groupdesc = tsClean($_POST['groupdesc']);
		
			if($groupname=='' || $groupdesc=='') tsNotice("小组名称和介绍都不能为空！");
			
			//过滤内容开始
			aac('system')->antiWord($groupname);
			aac('system')->antiWord($groupdesc);
			//过滤内容结束
			
			$isgroupname = $new['group']->findCount('group',array(
				'groupname'=>$groupname,
			));
			
			if($isgroupname > 0 && $strGroup['groupname']!=$groupname) tsNotice('小组名称已经存在！');
			
			
			$new['group']->update('group',array(
				'groupid'=>$groupid,
			),array(
				'groupname'	=> $groupname,
				'groupdesc'	=> $groupdesc,
				'joinway'		=> intval($_POST['joinway']),
				'ispost'	=> intval($_POST['ispost']),
				'isopen'		=> intval($_POST['isopen']),
				'ispostaudit'		=> intval($_POST['ispostaudit']),
			));
			
			// 处理标签
			if ($_POST ['tag']) {
				aac ( 'tag' )->delIndextag ( 'group', 'groupid', $groupid );
				aac ( 'tag' )->addTag ( 'group', 'groupid', $groupid, $_POST ['tag'] );
			}
			
			tsNotice('基本信息修改成功！');
		
		}else{
			
			tsNotice('非法操作！');
			
		}
		
		break;
		
	//删除帖子
	case "deltopic":
	
		//普通用户不允许删除内容
		if($TS_SITE['isallowdelete'] && $TS_USER ['isadmin'] == 0) tsNotice('系统不允许用户删除内容，请联系管理员删除！');
		
		$topicid = intval($_GET['topicid']);
		
		$strTopic = $new['group']->find('group_topic',array('topicid'=>$topicid));
		
		$groupid = $strTopic['groupid'];
		
		$strGroup = $new['group']->find('group',array('groupid'=>$groupid));
		
		$strGroupUser = $new['group']->find('group_user',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
		));
		
		//系统管理员删除
		if($TS_USER['isadmin'] == '1'){
			$new['group']->delTopic($topicid);
			
			header('Location: '.tsUrl('group'));
			exit;
			
		}
		
		//其他人员删除
		if($userid == $strTopic['userid'] || $userid == $strGroup['userid'] || $strGroupUser['isadmin']=='1'){
			
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isdelete'=>1,
			));
			
			// 扣除用户相应的积分，删除帖子扣5分
			aac('user')->delScore($strTopic['userid'],'删除帖子',5);
			
			tsNotice('你的删除帖子申请已经提交！');
			
		}
		
		break;
		
	//收藏帖子
	case "topic_collect":
		
		$topicid = intval($_POST['topicid']);
		
		$strTopic = $db->once_fetch_assoc("select * from ".dbprefix."group_topic where topicid='".$topicid."'");
		
		$collectNum = $db->once_num_rows("select * from ".dbprefix."group_topic_collect where userid='$userid' and topicid='$topicid'");
		
		if($userid == '0'){
			echo 0;
		}elseif($userid == $strTopic['userid']){
			echo 1;
		}elseif($collectNum > 0){
			echo 2;
		}else{
			
			$new['group']->create('group_topic_collect',array(
				'userid'=>$userid,
				'topicid'=>$topicid,
				'addtime'=>time(),
			));
			
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'count_love'=>$strTopic['count_love']+1,
			));
			
			echo 3;
		}
		
		break;

	//置顶帖子
	case "topic_istop":
		
		$topicid = intval($_GET['topicid']);

		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$topicid,
		));
		
		$istop = $strTopic['istop'];
		
		$istop == 0 ? $istop = 1 : $istop = 0;
		
		$strGroup = $new['group']->find('group',array(
			'groupid'=>$strTopic['groupid'],
		));
		
		if($userid==$strGroup['userid'] || $TS_USER['isadmin']==1){
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'istop'=>$istop,
			));
			
			
			tsNotice("帖子置顶成功！");
			
			
		}else{
			tsNotice("非法操作！");
		}
		break;
	
	//帖子标签
	case "topic_tag_ajax";
		
		$topicid = intval($_GET['topicid']);
		include template("topic_tag_ajax");
		break;
	
	//添加帖子标签
	case "topic_tag_do":
		
		$topicid = intval($_POST['topicid']);
		
		if($topicid == 0) tsNotice("非法操作！");
		
		$tagname = t($_POST['tagname']);
		$uptime	= time();
		
		if($tagname != ''){
		
			if(strlen($tagname) > '32') tsNotice("TAG长度大于32个字节（不能超过16个汉字）");
			
			$tagcount = $db->once_num_rows("select * from ".dbprefix."tag where tagname='".$tagname."'");
			
			if($tagcount == '0'){
				$db->query("INSERT INTO ".dbprefix."tag (`tagname`,`uptime`) VALUES ('".$tagname."','".$uptime."')");
				$tagid = $db->insert_id();
				
				$tagIndexCount = $db->once_num_rows("select * from ".dbprefix."tag_topic_index where topicid='".$topicid."' and tagid='".$tagid."'");
				if($tagIndexCount == '0'){
					$db->query("INSERT INTO ".dbprefix."tag_topic_index (`topicid`,`tagid`) VALUES ('".$topicid."','".$tagid."')");
				}
				
				$tagIdCount = $db->once_num_rows("select * from ".dbprefix."tag_topic_index where tagid='".$tagid."'");
				
				$db->query("update ".dbprefix."tag set `count_topic`='".$tagIdCount."',`uptime`='".$uptime."' where tagid='".$tagid."'");
				
			}else{
				
				$tagData = $db->once_fetch_assoc("select * from ".dbprefix."tag where tagname='".$tagname."'");
				
				$tagIndexCount = $db->once_num_rows("select * from ".dbprefix."tag_topic_index where topicid='".$topicid."' and tagid='".$tagData['tagid']."'");
				if($tagIndexCount == '0'){
					$db->query("INSERT INTO ".dbprefix."tag_topic_index (`topicid`,`tagid`) VALUES ('".$topicid."','".$tagData['tagid']."')");
				}
				
				$tagIdCount = $db->once_num_rows("select * from ".dbprefix."tag_topic_index where tagid='".$tagData['tagid']."'");
				
				$db->query("update ".dbprefix."tag set `count_topic`='".$tagIdCount."',`uptime`='".$uptime."' where tagid='".$tagData['tagid']."'");
				
			}
			
			echo "<script language=JavaScript>parent.window.location.reload();</script>";
			
		}
		
		break;
		
	//帖子分类
	case "topic_type":
	
		$groupid = intval($_POST['groupid']);
		$typename = t($_POST['typename']);
		if($typename != ''){
			$new['group']->create('group_topic_type',array(
				'groupid'=>$groupid,
				'typename'=>$typename,
			));
		}
		
		header("Location: ".tsUrl('group','edit',array('groupid'=>$groupid,'ts'=>'type')));
		
		break;
			
	//回复评论
	case "recomment":
	
		if($_POST['token'] != $_SESSION['token']) {
			echo 1;exit;
		}
		
		$referid = intval($_POST['referid']);
		$topicid = intval($_POST['topicid']);
		$content = tsClean($_POST['content']);
		
		$new['group']->create('group_topic_comment',array(
			'referid'=>$referid,
			'topicid'=>$topicid,
			'userid'=>$userid,
			'content'=>$content,
			'addtime'=>time(),
		));
		
		
		//统计评论数
		$count_comment = $new['group']->findCount('group_topic_comment',array(
			'topicid'=>$topicid,
		));
		
		//更新帖子最后回应时间和评论数
		$new['group']->update('group_topic',array(
			'topicid'=>$topicid,
		),array(
			'count_comment'=>$count_comment,
			'uptime'=>time(),
		));
		
		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$topicid,
		));
		
		$strComment = $new['group']->find('group_topic_comment',array(
			'commentid'=>$referid,
		));
		
		if($topicid && $strTopic['userid'] != $TS_USER['userid']){
			$msg_userid = '0';
			$msg_touserid = $strTopic['userid'];
			$msg_content = '你的帖子：《'.$strTopic['title'].'》新增一条评论，快去看看给个回复吧^_^ <br />'.tsUrl('group','topic',array('id'=>$topicid));
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		}
		
		if($referid && $strComment['userid'] != $TS_USER['userid']){
			$msg_userid = '0';
			$msg_touserid = $strComment['userid'];
			$msg_content = '有人评论了你在帖子：《'.$strTopic['title'].'》中的回复，快去看看给个回复吧^_^ <br />'.tsUrl('group','topic',array('id'=>$topicid));
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		}
		
		echo 0;exit;
		
		break;
	
	//编辑帖子类型
	case "edit_type":
		$typeid = intval($_POST['id']);
		
		$strType = $new['group']->find('group_topic_type',array(
			'typeid'=>$typeid,
		));
		
		$strGroup = $new['group']->find('group',array(
			'groupid'=>$strType['groupid'],
		));
		
		if($strGroup['userid']!=$userid){
			tsNotice('非法操作！');
		}
		
		$typename = t($_POST['value']);
		if(empty($typename)){
			echo '帖子类型不能为空，请点击继续编辑';
		}else{
			
			$new['group']->update('group_topic_type',array(
				'typeid'=>$typeid,
			),array(
				'typename'=>$typename,
			));
			
			echo $typename;
		}
		break;
	//删除帖子类型 
	case "del_type":
		$typeid = intval($_POST['typeid']);
		
		$strType = $new['group']->find('group_topic_type',array(
			'typeid'=>$typeid,
		));
		
		$strGroup = $new['group']->find('group',array(
			'groupid'=>$strType['groupid'],
		));
		
		if($strGroup['userid']!=$userid){
			echo 1;exit;//非法操作
		}
		
		$new['group']->delete('group_topic_type',array(
			'typeid'=>$typeid,
		));
		
		$new['group']->update('group_topic',array(
			'typeid'=>$typeid,
		),array(
			'typeid'=>0,
		));
		
		echo '0';
		break;
	
	case 'parseurl':
		function formPost($url,$post_data){
		  $o='';
		  foreach ($post_data as $k=>$v){
			  $o.= "$k=".urlencode($v)."&";
		  }
		  $post_data=substr($o,0,-1);
		  $ch = curl_init();
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		  curl_setopt($ch, CURLOPT_POST, 1);
		  curl_setopt($ch, CURLOPT_HEADER, 0);
		  curl_setopt($ch, CURLOPT_URL,$url);
		  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		  $result = curl_exec($ch);
		  return $result;
		}

		$url = $_POST['parseurl'];
		$urlArr = parse_url($url);
		$domainArr = explode('.',$urlArr['host']);
		$data['type'] = $domainArr[count($domainArr)-2];
		$str = formPost('http://share.pengyou.com/index.php?mod=usershare&act=geturlinfo',array('url'=>$url));
		echo $str;
	
		break;
		
	//置顶帖子 
	case "isposts":

		$topicid = intval($_GET['topicid']);
		
		if($userid == 0 || $topicid == 0) tsNotice("非法操作"); 
		
		$strTopic = $db->once_fetch_assoc("select userid,groupid,title,isposts from ".dbprefix."group_topic where topicid='$topicid'");
		
		$strGroup = $db->once_fetch_assoc("select userid from ".dbprefix."group where groupid='".$strTopic['groupid']."'");
		
		if($userid == $strGroup['userid'] || intval($TS_USER['isadmin']) == 1){
			if($strTopic['isposts']==0){
				$db->query("update ".dbprefix."group_topic set `isposts`='1' where `topicid`='$topicid'");
				
				//msg start
				$msg_userid = '0';
				$msg_touserid = $strTopic['userid'];
				$msg_content = '恭喜，你的帖子：《'.$strTopic['title'].'》被评为精华帖啦^_^ <br />'.tsUrl('group','topic',array('id'=>$topicid));
				aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
				//msg end
				
			}else{
				$db->query("update ".dbprefix."group_topic set `isposts`='0' where `topicid`='$topicid'");
			}
			
			tsNotice("操作成功！");
		}else{
			tsNotice("非法操作！");
		}
		
		break;
	
	//小组邀请用户 
	case "invite":
		
		$iuserid = intval($_POST['userid']);
		$groupid = intval($_POST['groupid']);
		
		if(aac('user')->isUser($iuserid) && $new['group']->isGroup($groupid)){
			
			//先统计用户有多少个小组了，20个封顶
			$userGroupNum = $new['group']->findCount('group_user',array('userid'=>$iuserid));
			
			if($userGroupNum >= 20) tsNotice('邀请用户加入的小组总数已经到达20个，不能再加入小组！');
			
			$groupUserNum = $new['group']->findCount('group_user',array(
				'userid'=>$iuserid,
				'groupid'=>$groupid,
			));
			
			if($groupUserNum > 0) tsNotice('用户已经加入小组！');
			
			$new['group']->create('group_user',array(
				'userid'=>$iuserid,
				'groupid'=>$groupid,
				'addtime'=>time(),
			));
			
			//计算小组会员数
			$count_user = $new['group']->findCount('group_user',array(
				'groupid'=>$groupid,
			));
			
			//更新小组成员统计
			$new['group']->update('group',array(
				'groupid'=>$groupid,
			),array(
				'count_user'=>$count_user,
			));
			
			//发送系统消息开始
			$msg_userid = '0';
			$msg_touserid = $iuserid;
			$msg_content = '你被邀请加入一个小组，快去看看吧<br />'
						.tsUrl('group','show',array('id'=>$groupid));
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
			//发送系统消息end
		
			header('Location: '.tsUrl('group','show',array('id'=>$groupid)));
		
		}else{
			tsNotice('倒霉了吧？');
		}
		
		break;
	
	//usertips
	case "usertips":
		
		$data = fileRead('data/user_tips.php');
		
		if($data == ''){
			$query = $db->fetch_all_assoc("select * from " . dbprefix . "user_info");
			foreach($query as $user) {
				$usertip[]=array('user'=>$user['username'],'name'=>$user['userid']);
			}
			fileWrite('user_tips.php','data',json_encode($usertip));
			$data = fileRead('data/user_tips.php');
		}
		
		echo $data;
		
		break;
}
