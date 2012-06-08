<?php
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

switch ($ts) {
	//简洁列表
	case "thems_other":
	$strUser = aac('user')->getOneUser($userid);
	if($strUser['thems_other']=='0') $thems_other=1;
	else $thems_other=0;
	
	$db -> query("update " . dbprefix . "user_info set thems_other ='$thems_other' where userid='$userid'"); 
	header('Location: '.SITE_URL);
	break;
	//加入该小组
	case "join":
		
		$groupid = intval($_GET['groupid']);
		
		$strGroup = $new['group']->find('group',array(
			'groupid'=>$groupid
		));
		
		//管理员可以加入任何小组
		if($TS_USER['user']['isadmin'] != 1){
			
			//除管理员外其他用户都要经过这一关审核
			if($strGroup['joinway'] == 1) tsNotice('本小组禁止加入！');
			
			//先统计用户有多少个小组了，50个封顶
			$userGroupNum = $new['group']->findCount('group_users',array('userid'=>$userid));
			
			if($userGroupNum >= $TS_APP['options']['joinnum']) tsNotice('你加入的小组总数已经到达'.$TS_APP['options']['joinnum'].'个，不能再加入小组！');
			
			$groupUserNum = $new['group']->findCount('group_users',array(
				'userid'=>$userid,
				'groupid'=>$groupid,
			));
			
			if($groupUserNum > 0) tsNotice('你已经加入小组！');
		
		}
		
		$new['group']->create('group_users',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
			'addtime'=>time(),
		));
		
		//计算小组会员数
		$count_user = $new['group']->findCount('group_users',array(
			'groupid'=>$groupid,
		));
		
		//更新小组成员统计
		$new['group']->update('group',array(
			'groupid'=>$groupid,
		),array(
			'count_user'=>$count_user,
		));
		
		header('Location: '.SITE_URL.tsUrl('group','show',array('id'=>$groupid)));

		break;
	
	//退出该小组
	case "exit":
		
		$groupid = intval($_GET['groupid']);
		
		//判断是否是组长，是组长不能退出小组
		$strGroup = $new['group']->find('group',array(
			'groupid'=>$groupid
		));
		
		if($strGroup['userid'] == $userid) tsNotice('组长任务艰巨，请坚持到底！');
		
		$new['group']->delete('group_users',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
		));
		
		//计算小组会员数
		$count_user = $new['group']->findCount('group_users',array(
			'groupid'=>$groupid,
		));
		
		//更新小组成员统计
		$new['group']->update('group',array(
			'groupid'=>$groupid,
		),array(
			'count_user'=>$count_user,
		));
	
		header('Location: '.SITE_URL.tsUrl('group','show',array('id'=>$groupid)));
		
		break;
	
	//上传小组头像
	
	case "groupicon":
		
		$groupid = intval($_POST['groupid']);
		
		//上传
		$arrUpload = tsUpload($_FILES['picfile'],$groupid,'group',array('jpg','gif','png'));
		
		if($arrUpload){

			$new['group']->update('group',array(
				'groupid'=>$groupid,
			),array(
				'path'=>$arrUpload['path'],
				'groupicon'=>$arrUpload['url'],
			));
			
			tsNotice("小组图标修改成功！");
			
		}else{
			tsNotice("上传出问题啦！");
		}
		
		break;
	
	//编辑小组基本信息
	case "edit_base":
	
		if($_POST['groupname']=='' || $_POST['groupdesc']=='') tsNotice("小组名称和介绍都不能为空！");
		
		$groupid = intval($_POST['groupid']);
		
		$new['group']->update('group',array(
			'groupid'=>$groupid,
		),array(
			'groupname'	=> trim($_POST['groupname']),
			'groupdesc'	=> trim($_POST['groupdesc']),
			'joinway'		=> intval($_POST['joinway']),
			'ispost'	=> intval($_POST['ispost']),
			'isopen'		=> intval($_POST['isopen']),
		));
		
		tsNotice('基本信息修改成功！');
		
		break;
		
	//删除帖子
	case "deltopic":
		
		$topicid = intval($_GET['topicid']);
		
		$strTopic = $new['group']->find('group_topics',array('topicid'=>$topicid));
		
		$groupid = $strTopic['groupid'];
		
		$strGroup = $new['group']->find('group',array('groupid'=>$groupid));
		
		$strGroupUser = $new['group']->find('group_users',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
		));
		
		if($userid == $strTopic['userid'] || $userid == $strGroup['userid'] || $strGroupUser['isadmin']=='1' || $TS_USER['user']['isadmin'] == '1'){
			
			$new['group']->delTopic($topicid);
			
			header("Location: ".SITE_URL);
			
		}else{
			tsNotice('搞毛啊！');
		}
		
		break;
		
	//收藏帖子
	case "topic_collect":
		
		$topicid = $_POST['topicid'];
		
		$strTopic = $db->once_fetch_assoc("select * from ".dbprefix."group_topics where topicid='".$topicid."'");
		
		$collectNum = $db->once_num_rows("select * from ".dbprefix."group_topics_collects where userid='$userid' and topicid='$topicid'");
		
		if($userid == '0'){
			echo 0;
		}elseif($userid == $strTopic['userid']){
			echo 1;
		}elseif($collectNum > 0){
			echo 2;
		}else{
			$db->query("insert into ".dbprefix."group_topics_collects (`userid`,`topicid`,`addtime`) values ('".$userid."','".$topicid."','".time()."')");
			$db->query("update  ".dbprefix."group_topics set count_love=count_love+1 where topicid='$topicid' ");
			echo 3;
		}
		
		break;
	//关闭帖子	
	case "topic_isclose":
	    
		$topicid = intval($_GET['topicid']);
	   
	    $strTopic = $db->once_fetch_assoc("select userid,groupid,isclose from ".dbprefix."group_topics where topicid='$topicid'");
		
		$isclose = $strTopic['isclose'];
		
		$isclose == 0 ? $isclose = 1 : $isclose = 0;
		
		$strGroup = $db->once_fetch_assoc("select userid from ".dbprefix."group where groupid='".$strTopic['groupid']."'");
		if($userid!=$strGroup['userid'] || $TS_USER['user']['isadmin']==1){
			$db->query("update ".dbprefix."group_topics set isclose='$isclose' where topicid='$topicid'");
			tsNotice("帖子操作成功！");
		}else{
			tsNotice("非法操作！");
		}
	    break;
		//帖子高亮	
	case "topic_color":
	    
		$topicid = intval($_GET['topicid']);
	    $color = intval($_GET['color']);
		$strTopic = $db->once_fetch_assoc("select userid,groupid from ".dbprefix."group_topics where topicid='$topicid'");
		$strGroup = $db->once_fetch_assoc("select userid from ".dbprefix."group where groupid='".$strTopic['groupid']."'");
		if($userid!=$strGroup['userid'] || $TS_USER['user']['isadmin']==1){
			$db->query("update ".dbprefix."group_topics set color='$color' where topicid='$topicid'");
			tsNotice("帖子操作成功！");
		}else{
			tsNotice("非法操作！");
		}
	    break;
	//置顶帖子
	case "topic_istop":
		
		$topicid = intval($_GET['topicid']);
		
		$strTopic = $db->once_fetch_assoc("select userid,groupid,istop from ".dbprefix."group_topics where topicid='$topicid'");
		
		$istop = $strTopic['istop'];
		
		$istop == 0 ? $istop = 1 : $istop = 0;
		
		$strGroup = $db->once_fetch_assoc("select userid from ".dbprefix."group where groupid='".$strTopic['groupid']."'");
		
		if($userid!=$strGroup['userid'] || $TS_USER['user']['isadmin']==1){
			$db->query("update ".dbprefix."group_topics set istop='$istop' where topicid='$topicid'");
			tsNotice("帖子置顶成功！");
		}else{
			tsNotice("非法操作！");
		}
		break;
		
	//隐藏显示帖子
	case "topic_isshow":
		
		$topicid =intval($_GET['topicid']);
		
		$strTopic = $db->once_fetch_assoc("select userid,groupid,isshow from ".dbprefix."group_topics where `topicid`='$topicid'");
		
		$strGroup = $db->once_fetch_assoc("select userid from ".dbprefix."group where `groupid`='".$strTopic['groupid']."'");
		
		$isshow = intval($strTopic['isshow']);
		
		$isshow == 0 ? $isshow = 1 : $isshow = 0;
		
		if($userid == $strGroup['userid'] || $TS_USER['user']['isadmin']==1){
			$db->query("update ".dbprefix."group_topics set isshow='$isshow' where topicid='$topicid'");
			tsNotice("操作成功！");
		}else{
			tsNotice("非法操作！");
		}
		
		break;
	
	//帖子标签
	case "topic_tag_ajax";
		
		$topicid = $_GET['topicid'];
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
		if($typename != '')
		  $db->query("insert into ".dbprefix."group_topics_type (`groupid`,`typename`) values ('$groupid','$typename')");
		
		header("Location: ".SITE_URL.tsUrl('group','edit',array('groupid'=>$groupid,'ts'=>'type')));
		
		break;
			
	//回复评论
	case "recomment":
		
		$referid = $_POST['referid'];
		$topicid = $_POST['topicid'];
		$content = trim($_POST['content']);
		$addtime = time();

		$db->query("insert into ".dbprefix."group_topics_comments (`referid`,`topicid`,`userid`,`content`,`addtime`) values ('$referid','$topicid','$userid','$content','$addtime')");
		
		//统计评论数
		$count_comment = $db->once_num_rows("select * from ".dbprefix."group_topics_comments where topicid='$topicid'");
		
		//更新帖子最后回应时间和评论数
		$uptime = time();
		
		$db->query("update ".dbprefix."group_topics set uptime='$uptime',count_comment='$count_comment' where topicid='$topicid'");
		
		$strTopic = $db->once_fetch_assoc("select * from ".dbprefix."group_topics where topicid='$topicid'");
		$strComment = $db->once_fetch_assoc("select * from ".dbprefix."group_topics_comments where commentid='$referid'");
		
		if($topicid && $strTopic['userid'] != $TS_USER['user']['userid']){
			$msg_userid = '0';
			$msg_touserid = $strTopic['userid'];
			$msg_content = '你的帖子：《'.$strTopic['title'].'》新增一条评论，快去看看给个回复吧^_^ <br />'.SITE_URL.tsUrl('group','topic',array('id'=>$topicid));
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		}
		
		if($referid && $strComment['userid'] != $TS_USER['user']['userid']){
			$msg_userid = '0';
			$msg_touserid = $strComment['userid'];
			$msg_content = '有人评论了你在帖子：《'.$strTopic['title'].'》中的回复，快去看看给个回复吧^_^ <br />'.SITE_URL.tsUrl('group','topic',array('id'=>$topicid));
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		}
		
		echo '0';
		
		break;
	
	//编辑帖子类型
	case "edit_type":
		$typeid = $_POST['id'];
		$typename = t($_POST['value']);
		if(empty($typename)){
			echo '帖子类型不能为空，请点击继续编辑';
		}else{
			$db->query("update ".dbprefix."group_topics_type set `typename`='$typename' where typeid='$typeid'");
			echo $typename;
		}
		break;
	//删除帖子类型 
	case "del_type":
		$typeid = $_POST['typeid'];
		$db->query("delete from ".dbprefix."group_topics_type where typeid='$typeid'");
		$db->query("update ".dbprefix."group_topics set typeid='0' where typeid='$typeid'");
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
		
	//移动帖子
	case "topic_move":
		$groupid = intval($_POST['groupid']);
		$topicid = intval($_POST['topicid']);

		$new['group']->update('group_topics',array(
			'topicid'=>$topicid,
		),array(
			'groupid'=>$groupid,
			'typeid'=>'0',
		));
		
		header("Location: ".SITE_URL.tsUrl('group','topic',array('id'=>$topicid)));
		
		break;
		
	//置顶帖子 
	case "isposts":

		$topicid = intval($_GET['topicid']);
		
		if($userid == 0 || $topicid == 0) tsNotice("非法操作"); 
		
		$strTopic = $db->once_fetch_assoc("select userid,groupid,title,isposts from ".dbprefix."group_topics where topicid='$topicid'");
		
		$strGroup = $db->once_fetch_assoc("select userid from ".dbprefix."group where groupid='".$strTopic['groupid']."'");
		
		if($userid == $strGroup['userid'] || intval($TS_USER['user']['isadmin']) == 1){
			if($strTopic['isposts']==0){
				$db->query("update ".dbprefix."group_topics set `isposts`='1' where `topicid`='$topicid'");
				
				//msg start
				$msg_userid = '0';
				$msg_touserid = $strTopic['userid'];
				$msg_content = '恭喜，你的帖子：《'.$strTopic['title'].'》被评为精华帖啦^_^ <br />'.SITE_URL.tsUrl('group','topic',array('id'=>$topicid));
				aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
				//msg end
				
			}else{
				$db->query("update ".dbprefix."group_topics set `isposts`='0' where `topicid`='$topicid'");
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
			$userGroupNum = $new['group']->findCount('group_users',array('userid'=>$iuserid));
			
			if($userGroupNum >= 20) tsNotice('邀请用户加入的小组总数已经到达20个，不能再加入小组！');
			
			$groupUserNum = $new['group']->findCount('group_users',array(
				'userid'=>$iuserid,
				'groupid'=>$groupid,
			));
			
			if($groupUserNum > 0) tsNotice('用户已经加入小组！');
			
			$new['group']->create('group_users',array(
				'userid'=>$iuserid,
				'groupid'=>$groupid,
				'addtime'=>time(),
			));
			
			//计算小组会员数
			$count_user = $new['group']->findCount('group_users',array(
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
						.SITE_URL.tsUrl('group','show',array('id'=>$groupid));
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
			//发送系统消息end
		
			header('Location: '.SITE_URL.tsUrl('group','show',array('id'=>$groupid)));
		
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
