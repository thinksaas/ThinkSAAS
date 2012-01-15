<?php
defined('IN_TS') or die('Access Denied.');

$userid = intval($TS_USER['user']['userid']);

if($userid == 0){
	header("Location: ".SITE_URL.tsurl('user','login'));
	exit;
}

switch($ts){
	case "":
		$groupid = intval($_GET['groupid']);
		//小组数目
		$groupNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."group where groupid='$groupid'");

		if($groupNum['count(*)'] == 0){
			header("Location: ".SITE_URL);
			exit;
		}

		//小组会员
		$isGroupUser = $db->once_fetch_assoc("select count(*) from ".dbprefix."group_users where userid='$userid' and groupid='$groupid'");

		$strGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where groupid='$groupid'");

		//允许小组成员发帖
		if($strGroup['ispost']==0 && $isGroupUser['count(*)'] == 0 && $userid != $strGroup['userid']){
			
			qiMsg("本小组只允许小组成员发贴，请加入小组后再发帖！");
			
		}

		//不允许小组成员发帖
		if($strGroup['ispost'] == 1 && $userid != $strGroup['userid']){
			qiMsg("本小组只允许小组组长发帖！");
		}

		//帖子类型
		$arrGroupType = $db->fetch_all_assoc("select * from ".dbprefix."group_topics_type where groupid='".$strGroup['groupid']."'");

		$title = L::addtopic_newthread;

		//包含模版
		include template("addtopic");
		break;
	
	//执行发布帖子
	case "do":
	
		$groupid	= intval($_POST['groupid']);
		
		$title	= htmlspecialchars(trim($_POST['title']));
		$content	= trim($_POST['content']);
		
		$tag = trim($_POST['tag']);
		
		//发布帖子标签
		doAction('group_topic_add',$title,$content,$tag);
		
		$typeid = intval($_POST['typeid']);
		
		$iscomment = $_POST['iscomment'];
		
		
		if($title==''){

			qiMsg('不要这么偷懒嘛，多少请写一点内容哦^_^');
			
		}elseif($content==''){

			qiMsg('没有任何内容是不允许你通过滴^_^');
			
		}elseif(mb_strlen($title,'utf8')>64){//限制发表内容多长度，默认为30
			
		 	qiMsg('标题很长很长很长很长...^_^');
		
		}elseif(mb_strlen($content,'utf8')>20000){//限制发表内容多长度，默认为1w
			
		 	qiMsg('发这么多内容干啥^_^');
		
		}else{
			
			$uptime = time();
			
			$arrData = array(
				'groupid'				=> $groupid,
				'typeid'	=> $typeid,
				'userid'				=> $TS_USER['user']['userid'],
				'title'				=> $title,
				'content'		=> $content,
				'iscomment'		=> $iscomment,
				'addtime'			=> time(),
				'uptime'	=> $uptime,
			);
			
			//判断是否有图片和附件
			preg_match_all('/\[(photo)=(\d+)\]/is', $content, $isphoto);
			if($isphoto[2]){
				$arrData['isphoto'] = '1';
			}
			//判断附件
			preg_match_all('/\[(attach)=(\d+)\]/is', $content, $isattach);
			if($isattach[2]){
				$arrData['isattach'] = '1';
			}
			
			$topicid = $db->insertArr($arrData,dbprefix.'group_topics');
			
			$strGroup = $db->once_fetch_assoc("select groupid,groupname from ".dbprefix."group where `groupid`='$groupid'");
			
			//统计帖子类型 
			if($typeid != '0'){
				$topicTypeNum = $db->once_num_rows("select * from ".dbprefix."group_topics where typeid='$typeid'");
				$db->query("update ".dbprefix."group_topics_type set `count_topic`='$topicTypeNum' where typeid='$typeid'");
			}
			//处理标签
			aac('tag')->addTag('topic','topicid',$topicid,$tag);
			
			//统计小组下帖子数并更新
			$count_topic = $db->once_num_rows("select * from ".dbprefix."group_topics where groupid='$groupid'");
			
			//统计今天发布帖子数
			$today_start = strtotime(date('Y-m-d 00:00:00'));
			$today_end = strtotime(date('Y-m-d 23:59:59'));
			
			$count_topic_today = $db->once_num_rows("select * from ".dbprefix."group_topics where groupid='$groupid' and addtime > '$today_start'");
			
			
			$db->query("update ".dbprefix."group set count_topic='$count_topic',count_topic_today='$count_topic_today',uptime='$uptime' where groupid='$groupid'");
			
			
			
			//积分记录
			$userid = $TS_USER['user']['userid'];
			$db->query("insert into ".dbprefix."user_scores (`userid`,`scorename`,`score`,`addtime`) values ('".$userid."','发帖','50','".time()."')");
			
			$strScore = $db->once_fetch_assoc("select sum(score) score from ".dbprefix."user_scores where userid='".$userid."'");
			
			//更新积分
			$db->query("update ".dbprefix."user_info set `count_score`='".$strScore['score']."' where userid='$userid'");
			
			//feed开始
			$feed_template = '<span class="pl">在 <a href="{group_link}">{group_name}</a> 创建了新话题：<a href="{topic_link}">{topic_title}</a></span><div class="broadsmr">{content}</div><div class="indentrec"><span><a  class="j a_rec_reply" href="{topic_link}">回应</a></span></div>';
			$feed_data = array(
				'group_link'	=> SITE_URL.tsurl('group','g',array('id'=>$strGroup['groupid'])),
				'group_name'	=> $strGroup['groupname'],
				'topic_link'	=> SITE_URL.tsurl('group','t',array('id'=>$topicid)),
				'topic_title'	=> $title,
				'content'	=> getsubstrutf8(t($content),'0','50'),
			);
			aac('feed')->addFeed($userid,$feed_template,serialize($feed_data));
			//feed结束
			
			header("Location: ".SITE_URL.tsurl('group','t',array('id'=>$topicid)));
			
		}
	
		break;
}