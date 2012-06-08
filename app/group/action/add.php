<?php
defined('IN_TS') or die('Access Denied.');
// 用户是否登录
$userid = aac('user') -> isLogin();

switch ($ts) {
	//发布帖子
	case "":

		$groupid = intval($_GET['id']); 
		// 小组数目
		$groupNum = $new['group']->findCount('group',array(
			'groupid'=>$groupid,
		));

		if ($groupNum == 0) {
			header("Location: " . SITE_URL);
			exit;
		}

		// 小组会员
		$isGroupUser = $db -> once_fetch_assoc("select count(*) from " . dbprefix . "group_users where userid='$userid' and groupid='$groupid'");

		$strGroup = $db -> once_fetch_assoc("select * from " . dbprefix . "group where groupid='$groupid'"); 
		// 允许小组成员发帖
		if ($strGroup['ispost'] == 0 && $isGroupUser['count(*)'] == 0 && $userid != $strGroup['userid']) {
			tsNotice("本小组只允许小组成员发贴，请加入小组后再发帖！");
		} 
		// 不允许小组成员发帖
		if ($strGroup['ispost'] == 1 && $userid != $strGroup['userid']) {
			tsNotice("本小组只允许小组组长发帖！");
		} 
		// 帖子类型
		$arrGroupType = $db -> fetch_all_assoc("select * from " . dbprefix . "group_topics_type where groupid='" . $strGroup['groupid'] . "'");

		$title = '发布帖子'; 
		// 包含模版
		include template("add");

		break; 
		
	// 执行发布帖子
	case "do":
        $groupid = intval($_POST['groupid']);
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		$typeid = intval($_POST['typeid']);
	
		$tag = trim($_POST['tag']); 
		
			// 发布帖子标签
			doAction('group_topic_add', $title, $content, $tag);

			$photoshow = intval($_POST['photoshow']); //图片是否回复显示
			$attachshow = intval($_POST['attachshow']); //附件是否回复显示
			$attachscore = intval($_POST['attachscore']); //附件下载积分
			$iscomment = $_POST['iscomment'];

			if ($title == '') {
				tsNotice('不要这么偷懒嘛，多少请写一点内容哦^_^');
			} elseif ($content == '') {
				tsNotice('没有任何内容是不允许你通过滴^_^');
			}else {

			$arrData = array('groupid' => $groupid,
				'typeid' => $typeid,
				'userid' => $TS_USER['user']['userid'],
				'title' => $title,
				'content' => $content,
				'iscomment' => $iscomment,
				'addtime' => time(),
				'uptime' => time(),
				);
		   
			$topicid = $db -> insertArr($arrData, dbprefix . 'group_topics');

			
			// 上传帖子图片开始
			$arrUpload = tsUpload($_FILES['picfile'], $topicid, 'topic', array('jpg', 'gif', 'png','jpeg'));
			if ($arrUpload) {
				$new['group'] -> update('group_topics', array('topicid' => $topicid,
						), array('path' => $arrUpload['path'],
						'photo' => $arrUpload['url'],
						'photoshow' => $photoshow,
						));
			} 
			// 上传帖子图片结束
			// 上传附件开始
			$attUpload = tsUpload($_FILES['attfile'], $topicid, 'topic', array('zip', 'rar', 'doc', 'txt', 'pdf', 'ppt', 'docx', 'xls', 'xlsx'));
			if ($attUpload) {
				$new['group'] -> update('group_topics', array('topicid' => $topicid,
						), array('path' => $attUpload['path'],
						'attach' => $attUpload['url'],
						'attachname' => $attUpload['name'],
						'attachshow' => $attachshow,
						'attachscore' => $attachscore,
						));
			} 
			// 上传附件结束

			// 处理视频
			$video = trim($_POST['video']);
			$arrVideoType = explode('.', strtolower($video)); //转小写一下
			$videoType = array_pop($arrVideoType);
			if (in_array($videoType, array('swf'))) {
				$header = get_headers($video);
				if ($header[0] == 'HTTP/1.0 200 OK' || $header[0] == 'HTTP/1.1 200 OK' || $header[0] == 'HTTP/1.0 302 Found' || $header[0] == 'HTTP/1.1 302 Found' || $header[0] == 'HTTP/1.0 302 Moved Temporarily' || $header[0] == 'HTTP/1.1 302 Moved Temporarily') {
					$new['group'] -> update('group_topics', array('topicid' => $topicid,
							), array('video' => $video,
							));
				} 
			} 
		} 
		
		//处理@用户名
		if (preg_match_all('/@/', $content, $at)) {
			preg_match_all("/@(.+?)([\s|:]|$)/is", $content, $matches);

			$unames = $matches[1];

			$ns = "'" . implode("','", $unames) . "'";

			$csql = "username IN($ns)";

			if ($unames) {
			
				$query = $db -> fetch_all_assoc("select userid,username from " . dbprefix . "user_info where $csql");

				foreach($query as $v) {
					$content = str_replace('@' . $v['username'] . '', '[@' . $v['username'] . ':' . $v['userid'] . ']', $content);
					$msg_content = '我在帖子中提到了你<br />去看看：' . SITE_URL . tsUrl('group', 'topic', array('id' => $topicid));
					aac('message') -> sendmsg($TS_USER['user']['userid'], $v['userid'], $msg_content);
				} 

				$new['group'] -> update('group_topics',array(
					'topicid' => $topicid
				),array(
					'content' => $content
				));
			} 
		}
		
		
		$strGroup = $db -> once_fetch_assoc("select groupid,groupname from " . dbprefix . "group where `groupid`='$groupid'"); 
		// 统计帖子类型
		if ($typeid != '0') {
			$topicTypeNum = $db -> once_num_rows("select * from " . dbprefix . "group_topics where typeid='$typeid'");
			$db -> query("update " . dbprefix . "group_topics_type set `count_topic`='$topicTypeNum' where typeid='$typeid'");
		} 
		// 处理标签
		aac('tag') -> addTag('topic', 'topicid', $topicid, $tag); 
		// 统计小组下帖子数并更新
		$count_topic = $db -> once_num_rows("select * from " . dbprefix . "group_topics where groupid='$groupid'"); 
		// 统计今天发布帖子数
		$today_start = strtotime(date('Y-m-d 00:00:00'));
		$today_end = strtotime(date('Y-m-d 23:59:59'));

		$count_topic_today = $db -> once_num_rows("select * from " . dbprefix . "group_topics where groupid='$groupid' and addtime > '$today_start'");

		$db -> query("update " . dbprefix . "group set count_topic='$count_topic',count_topic_today='$count_topic_today',uptime='".time()."' where groupid='$groupid'"); 
		// 积分记录
		$userid = $TS_USER['user']['userid'];
		$db -> query("insert into " . dbprefix . "user_scores (`userid`,`scorename`,`score`,`addtime`) values ('" . $userid . "','发帖','50','" . time() . "')");

		$strScore = $db -> once_fetch_assoc("select sum(score) score from " . dbprefix . "user_scores where userid='" . $userid . "'"); 
		// 更新积分
		$db -> query("update " . dbprefix . "user_info set `count_score`='" . $strScore['score'] . "' where userid='$userid'"); 
		// feed开始
		$feed_template = '<span class="pl">在 <a href="{group_link}">{group_name}</a> 创建了新话题：<a href="{topic_link}">{topic_title}</a></span><div class="broadsmr">{content}</div><div class="indentrec"><span><a  class="j a_rec_reply" href="{topic_link}">回应</a></span></div>';
		$feed_data = array(
			'group_link' => SITE_URL . tsUrl('group', 'show', array('id' => $strGroup['groupid'])),
			'group_name' => $strGroup['groupname'],
			'topic_link' => SITE_URL . tsUrl('group', 'topic', array('id' => $topicid)),
			'topic_title' => $title,
			'content' => getsubstrutf8($content, 0, 100),
			);
		aac('feed') -> add($userid, $feed_template, $feed_data); 
		header("Location: " . SITE_URL . tsUrl('group', 'topic', array('id' => $topicid)));
		break; 
	
	
	// 快速发帖
	case "speed":
		
		$groupNum = $new['group']->findCount('group_users',array(
			'userid'=>$userid,
		));
		
		if($groupNum == 0) tsNotice('你还没有加入任何小组，不允许发帖！');
		
		$myGroups = $new['group']->findAll('group_users',array(
			'userid'=>$userid,
		));
		
		$arrGroup = '';
		
		foreach($myGroups as $key=>$item){
			$strGroup = $new['group']->getOneGroup($item['groupid']);
			if($strGroup['ispost']==0){
				$arrGroup[] = $strGroup;
			}
		}
		
		if($arrGroup=='') tsNotice('你加入的小组不允许发帖子！');
		
		$title = '快速发帖';
		include template('add_speed');
		break;
} 
