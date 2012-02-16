<?php
defined('IN_TS') or die('Access Denied.');

//发表评论 
if($ts=='addcomment'){
	if (IS_POST) {
		
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0){
			header("Location: ".SITE_URL.tsurl('user','login'));
			exit;
		}
		
		$topicid	= intval($_POST['topicid']);
		$content	= trim($_POST['content']);
		
		//添加评论标签
		doAction('group_comment_add','',$content,'');
		
		if($content==''){
			tsNotice('没有任何内容是不允许你通过滴^_^');
		}else{
			
			$commentid = $db->create('group_topics_comments',array(
				'topicid'			=> $topicid,
				'userid'			=> $userid,
				'content'	=> $content,
				'addtime'		=> time(),
			));
			
			//统计评论数
			$count_comment = $db->findCount('group_topics_comments',array(
				'topicid'=>$topicid,
			));
			
			//更新帖子最后回应时间和评论数
			$uptime = time();
			
			$db->update('group_topics',array(
				'uptime'=>$uptime,
				'count_comment'=>$count_comment,
			),array(
				'topicid'=>$topicid,
			));
			
			//积分记录
			$db->create('user_scores',array(
				'userid'=>$userid,
				'scorename'=>'回帖',
				'score'=>20,
				'addtime'=>time(),
			));
			
			$strScore = $db->find("select sum(score) score from ".dbprefix."user_scores where userid='".$userid."'");
			
			//更新积分
			$db->update('user_info',array(
				'count_score'=>$strScore['score'],
			),array(
				'userid'=>$userid,
			));
			
			//发送系统消息(通知楼主有人回复他的帖子啦)
			$strTopic = $db->find("select * from ".dbprefix."group_topics where topicid='$topicid'");
			if($strTopic['userid'] != $TS_USER['user']['userid']){
			
				$msg_userid = '0';
				$msg_touserid = $strTopic['userid'];
				$msg_content = '你的帖子：《'.$strTopic['title'].'》新增一条评论，快去看看给个回复吧^_^ <br />'
											.SITE_URL.tsurl('group','topic',array('id'=>$topicid));
				aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
				
			}
			
			
			//feed开始
			$feed_template = '<span class="pl">评论了帖子：<a href="{link}">{title}</a></span><div class="quote"><span class="inq">{content}</span> <span><a class="j a_saying_reply" href="{link}" rev="unfold">回应</a>
    </span></div>';
			$feed_data = array(
				'link'	=> SITE_URL.tsurl('group','topic',array('id'=>$topicid)),
				'title'	=> $strTopic['title'],
				'content'	=> getsubstrutf8(t($content),'0','50'),
			);
			aac('feed')->addFeed($userid,$feed_template,serialize($feed_data));
			//feed结束
			
			
			header("Location: ".SITE_URL.tsurl('group','topic',array('id'=>$topicid)));
		}	
	}
}

switch ($ts) {

	case "addtopic":
	
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		
		if($userid == 0){
			header("Location: ".SITE_URL.tsurl('user','login'));
			exit;
		}
	
		$groupid	= intval($_POST['groupid']);
		
		$title	= htmlspecialchars(trim($_POST['title']));
		$content	= trim($_POST['content']);
		
		$tag = trim($_POST['tag']);
		
		//发布帖子标签
		doAction('group_topic_add',$title,$content,$tag);
		
		$typeid = intval($_POST['typeid']);
		
		$iscomment = $_POST['iscomment'];
		
		
		if($title==''){

			tsNotice('不要这么偷懒嘛，多少请写一点内容哦^_^');
			
		}elseif($content==''){

			tsNotice('没有任何内容是不允许你通过滴^_^');
			
		}elseif(mb_strlen($title,'utf8')>64){//限制发表内容多长度，默认为30
			
		 	tsNotice('标题很长很长很长很长...^_^');
		
		}elseif(mb_strlen($content,'utf8')>20000){//限制发表内容多长度，默认为1w
			
		 	tsNotice('发这么多内容干啥^_^');
		
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
			
			$topicid = $db->create(dbprefix.'group_topics',$arrData);
			
			$strGroup = $db->find("select groupid,groupname from ".dbprefix."group where `groupid`='$groupid'");
			
			//统计帖子类型 
			if($typeid != '0'){

				$topicTypeNum = $db->findCount('group_topics',array(
					'typeid'=>$typeid,
				));

				$db->update('group_topics_type',array(
					'count_topic'=>$topicTypeNum,
				),array(
					'typeid'=>$typeid,
				));
				
			}
			//处理标签
			aac('tag')->addTag('topic','topicid',$topicid,$tag);
			
			//统计小组下帖子数并更新
			$count_topic = $db->findCount('group_topics',array(
				'groupid'=>$groupid,
			));
			
			//统计今天发布帖子数
			$today_start = strtotime(date('Y-m-d 00:00:00'));
			$today_end = strtotime(date('Y-m-d 23:59:59'));
		
			$count_topic_today = $db->findCount('group_topics',"`groupid`='$groupid' and `addtime` > '$today_start'");
			
			$db->update('group',array(
				'count_topic'=>$count_topic,
				'count_topic_today'=>$count_topic_today,
				'uptime'=>time(),
			),array(
				'groupid'=>$groupid,
			));
			
			
			//积分记录
			$userid = $TS_USER['user']['userid'];
			
			$db->create('user_scores',array(
				'userid'=>$userid,
				'scorename'=>'发帖',
				'score'=>50,
				'addtime'=>tiem(),
			));
			
			$strScore = $db->find("select sum(score) score from ".dbprefix."user_scores where userid='".$userid."'");
			
			//更新积分
			$db->update('user_info',array(
				'count_score'=>$strScore['score'],
			),array(
				'userid'=>$userid,
			));
			
			
			
			//feed开始
			$feed_template = '<span class="pl">在 <a href="{group_link}">{group_name}</a> 创建了新话题：<a href="{topic_link}">{topic_title}</a></span><div class="broadsmr">{content}</div><div class="indentrec"><span><a  class="j a_rec_reply" href="{topic_link}">回应</a></span></div>';
			$feed_data = array(
				'group_link'	=> SITE_URL.tsurl('group','show',array('id'=>$strGroup['groupid'])),
				'group_name'	=> $strGroup['groupname'],
				'topic_link'	=> SITE_URL.tsurl('group','topic',array('id'=>$topicid)),
				'topic_title'	=> $title,
				'content'	=> getsubstrutf8(t($content),'0','50'),
			);
			aac('feed')->addFeed($userid,$feed_template,serialize($feed_data));
			//feed结束
			
			header("Location: ".SITE_URL.tsurl('group','topic',array('id'=>$topicid)));
			
		}
		break;

	//加入该小组
	case "joingroup":
		
		$userid = intval($TS_USER['user']['userid']);
		
		$groupid = intval($_POST['groupid']);

		$groupUserNum = $db->findCount('group_users',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
		));
		
		if($userid == '0'){
			echo '0';return false;
		}elseif($groupUserNum > 0){
			echo '1';return false;
		}else{
			
			$db->create('group_users',array(
				'userid'=>$userid,
				'groupid'=>$groupid,
				'addtime'=>time(),
			));
			
			//计算小组会员数
			$groupUserNum = $db->findCount('group_users',array(
				'groupid'=>$groupid,
			));
			//更新小组成员统计
			$db->update('group',array(
				'count_user'=>$groupUserNum,
			),array(
				'groupid'=>$groupid,
			));

			echo '2';return false;
		
		}
	
		break;
	
	//退出该小组
	case "exitgroup":
		
		$userid = intval($TS_USER['user']['userid']);
		
		$groupid = intval($_POST['groupid']);
		
		//判断是否是组长，是组长不能退出小组
		$strGroup = $db->find("select userid from ".dbprefix."group where groupid='$groupid'");
		
		if($userid == $strGroup['userid']){
			echo '0';return false;
		}else{
			$db->query("DELETE FROM ".dbprefix."group_users WHERE userid='$userid' and groupid='$groupid'");
			
			//计算小组会员数
			$groupUserNum = $db->findCount('group_users',array(
				'groupid'=>$groupid,
			));
			
			//更新小组统计
			$db->update('group',array(
				'count_user'=>$groupUserNum,
			),array(
				'groupid'=>$groupid,
			));
			
			echo '1';return false;
		}
		
		break;
	
	//上传小组头像
	
	case "groupicon":
		
		$groupid = intval($_POST['groupid']);
		
		//处理目录存储方式
		$menu = substr($groupid,0,1);
		
		$uptypes = array( 
			'image/jpg',
			'image/jpeg',
			'image/png',
			'image/pjpeg',
			'image/gif',
			'image/x-png',
		);

		if(isset($_FILES['picfile'])){
		
			$f=$_FILES['picfile'];
			
			if(empty($f['name'])){
			
				tsNotice("头像不能为空！");
				
			}elseif ($f['name']){
				if (!in_array($_FILES['picfile']['type'],$uptypes)) {
					tsNotice('你上传的头像图片类型不正确，系统仅支持 jpg,gif,png 格式的图片!');
				}
			} 
			
			//存储方式
			//1000个文件一个目录 
			$menu2=intval($groupid/1000);
			$menu1=intval($menu2/1000);
			$menu = $menu1.'/'.$menu2;
			
			$newdir = $menu;
			$dest_dir='uploadfile/group/'.$newdir;
			createFolders($dest_dir);
			
			//原图
			$fileInfo=pathinfo($f['name']);
			$extension=$fileInfo['extension'];
			$newphotoname = $groupid.'.'.$extension;
			$dest=$dest_dir.'/'.$newphotoname;

			move_uploaded_file($f['tmp_name'],mb_convert_encoding($dest,"gb2312","UTF-8"));
			chmod($dest, 0755);

			$groupicon = $newdir.'/'.$newphotoname;
			
			//更新小组头像
			$db->update('group',array(
				'path'=>$menu,
				'groupicon'=>$groupicon,
			),array(
				'groupid'=>$groupid,
			));

			tsNotice("小组图标修改成功！");
			
		}
		
		break;
	
	//编辑小组基本信息
	case "edit_base":
	
		if($_POST['groupname']=='' || $_POST['groupdesc']=='') tsNotice("小组名称和介绍都不能为空！");
		
		$groupid = intval($_POST['groupid']);
		
		$db->update('group',array(
			'groupname'	=> h($_POST['groupname']),
			'groupdesc'	=> trim($_POST['groupdesc']),
			'joinway'		=> intval($_POST['joinway']),
			'ispost'	=> intval($_POST['ispost']),
			'isopen'		=> intval($_POST['isopen']),
			'role_leader'	=> t($_POST['role_leader']),
			'role_admin'	=> t($_POST['role_admin']),
			'role_user'	=> t($_POST['role_user']),
		),array(
			'groupid'=>$groupid,
		));
		
		tsNotice('基本信息修改成功！');
		
		break;
	
	//添加小组分类索引
	case "addgroupcateindex":
		$groupid = $_POST['groupid'];
		$cateid = $_POST['cateid'];
		
		$uptime = time();
		
		if($cateid > 0){
			
			$db->create('group_cates_index',array(
				'groupid'=>$groupid,
				'cateid'=>$cateid,
			));
			
			//更新分类下小组数
			$groupnum = $db->findCount('group_cates',array(
				'cateid'=>$cateid,
			));
			
			$db->update('group_cates',array(
				'count_group'=>$groupnum,
				'uptime'=>$uptime,
			),array(
				'cateid'=>$cateid,
			));
			
			//判断是否有顶级分类
			$strCate = $db->find("select catereferid from ".dbprefix."group_cates where cateid='$cateid'");
			$catereferid = $strCate['catereferid'];
			
			if($catereferid > 0){
				//统计顶级分类下小组数
				$grouptotal = $db->find("select sum(`count_group`) as `total` from ".dbprefix."group_cates where catereferid='$catereferid'");
				
				$total = $grouptotal['total'];
				
				$db->update('group_cates',array(
					'count_group'=>$total,
					'uptime'=>$uptime,
				),array(
					'cateid'=>$catereferid,
				));
			}

			
		}
		
		echo '0';

		break;
	
	//添加话题附件
	case "topic_attach_add":
		
		if($_FILES['attach']['name'][0] == '') tsNotice("上传文件不能为空！");
		
		$groupid = $_POST['groupid'];
		$topicid = $_POST['topicid'];
		
		//处理目录存储方式
		$menu = substr($topicid,0,1);
		
		$date = date('Ymd');
		
		$dest_dir='uploadfile/group/topic/'.$menu.'/'.$date.'/'.$topicid;
		createFolders($dest_dir);
	
		$score = intval($_POST['score']);
		$isview = $_POST['isview'];
		
		if(isset($_FILES['attach'])){
			$arrFileName = $_FILES['attach']['name'];
			foreach($arrFileName as $key=>$item){
				if($item != ''){
					$attachname = $item;
					$attachtype = $_FILES['attach']['type'][$key];
					$attachsize = $_FILES['attach']['size'][$key];
					
					$dest=$dest_dir.'/'.$item;
					move_uploaded_file($_FILES['attach']['tmp_name'][$key], mb_convert_encoding($dest,"gb2312","UTF-8"));
					chmod($dest, 0755);
					
					$attachid = $db->create('group_topics_attachs',array(
						'userid'	=> $TS_USER['user']['userid'],
						'groupid'		=> $groupid,
						'topicid'		=> $topicid,
						'attachname'	=> $attachname,
						'attachtype'	=> $attachtype,
						'attachurl'		=> $dest,
						'attachsize'		=> $attachsize,
						'score'		=> $score,
						'isview'		=> $isview,
						'addtime'	=> time(),
					));
					
				}
				
			}
		}		
		
		//统计附件并更新
		$count_attach = $db->findCount('group_topics_attachs',array(
			'topicid'=>$topicid,
		));

		$db->update('group_topics',array(
			'count_attach'=>$count_attach,
		),array(
			'topicid'=>$topicid,
		));
		
		header("Location: ".SITE_URL.tsurl('group','topic',array('id'=>$topicid)));
		
		break;
	
	//创建小组
	case "group_add":
		
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0){
			header("Location: ".SITE_URL.tsurl('user','login'));
			exit;
		}
		
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
		
		$isGroup = $db->find("select count(groupid) from ".dbprefix."group where groupname='$groupname'");
		
		if($isGroup['count(groupid)'] > 0) tsNotice("小组名称已经存在，请更换其他小组名称！");
		
		$groupid = $db->create(dbprefix.'group',array(
			'userid'			=> $userid,
			'groupname'	=> $groupname,
			'groupdesc'		=> $_POST['groupdesc'],
			'isaudit'	=> $isaudit,
			'addtime'		=> time(),
		));
		$uptime = time();
		
		//绑定小组分类开始
		if($cateid > 0){
			
			$db->create('group_cates_index',array(
				'groupid'=>$groupid,
				'cateid'=>$cateid,
			));
			
			//更新分类下小组数
			$groupnum = $db->findCount('group_cates_index',array(
				'cateid'=>$cateid,
			));
			
			$db->update('group_cates',array(
				'count_group'=>$groupnum,
				'uptime'=>$uptime,
			),array(
				'cateid'=>$cateid,
			));
			
			//判断是否有顶级分类
			$strCate = $db->find("select catereferid from ".dbprefix."group_cates where cateid='$cateid'");
			$catereferid = $strCate['catereferid'];
			
			if($catereferid > 0){
				//统计顶级分类下小组数
				$grouptotal = $db->find("select sum(`count_group`) as `total` from ".dbprefix."group_cates where catereferid='$catereferid'");
				
				$total = $grouptotal['total'];

				$db->update('group_cates',array(
					'count_group'=>$total,
					'uptime'=>$uptime,
				),array(
					'cateid'=>$catereferid,
				));
			}
		}
		//绑定小组分类结束
		
		//绑定成员
		
		$db->create('group_user',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
			'addtime'=>time(),
		));
		
		//更新
		$db->update('group',array(
			'count_user'=>1,
		),array(
			'groupid'=>$groupid,
		));

		header("Location: ".SITE_URL.tsurl('group','show',array('id'=>$groupid)));
		
		break;
		
	//删除评论回帖
	case "comment_del":
		
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0){
			header("Location: ".SITE_URL.tsurl('user','login'));
			exit;
		}
		
		$commentid = intval($_GET['commentid']);
		
		$strComment = $db->find("select topicid from ".dbprefix."group_topics_comments where `commentid`='$commentid'");
		
		$strTopic = $db->find("select userid,groupid from ".dbprefix."group_topics where `topicid`='".$strComment['topicid']."'");
		
		$strGroup = $db->find("select userid from ".dbprefix."group where `groupid`='".$strTopic['groupid']."'");
		
		if($strTopic['userid']==$userid || $strGroup['userid']==$userid || $TS_USER['user']['isadmin']==1){
			
			$db->query("DELETE FROM ".dbprefix."group_topics_comments WHERE commentid = '".$commentid."'");
			//统计
			$db->update('group_topics',array(
				'count_comment'=>'count_comment-1',
			),array(
				'topicid'=>$strComment['topicid'],
			));
			
		}
		
		//跳转回到帖子页
		header("Location: ".SITE_URL.tsurl('group','topic',array('id'=>$strComment['topicid'])));
		
		
		break;
		
	//删除帖子
	case "topic_del":
	
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		
		$groupid = $_POST['groupid'];
		$topicid = $_POST['topicid'];
		
		
		$strGroup = $db->find("select userid from ".dbprefix."group where groupid='$groupid'");
		
		$strTopic = $db->find("select userid from ".dbprefix."group_topics where topicid='$topicid'");
		
		$strGroupUser = $db->find("select * from ".dbprefix."group_users where userid='$userid' and groupid='".$groupid."'");
		
		if($userid == $strTopic['userid'] || $userid == $strGroup['userid'] || $strGroupUser['isadmin']=='1' || $TS_USER['user']['isadmin'] == '1'){
			
			//删除帖子
			$db->query("DELETE FROM ".dbprefix."group_topics WHERE topicid = '".$topicid."'");
			//删除评论回复
			$db->query("DELETE FROM ".dbprefix."group_topics_comments WHERE topicid = '".$topicid."'");
			//删除tag索引
			$db->query("DELETE FROM ".dbprefix."tag_topic_index WHERE topicid = '".$topicid."'");
			
			//统计 
			$db->update('group',array(
				'count_topic'=>'count_topic-1',
			),array(
				'groupid'=>$groupid,
			));
			
			echo '0';
			
		}else{

			echo '1';
		
		}
		
		break;
		
	//编辑帖子 
	case "topic_eidt":
	
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0){
			header("Location: ".SITE_URL);
			exit;
		}
		
		$topicid = $_POST['topicid'];
		$title = htmlspecialchars(trim($_POST['title']));
		$typeid = $_POST['typeid'];
		$content = trim($_POST['content']);
		
		$iscomment = $_POST['iscomment'];
		
		if($topicid == '' || $title=='' || $content=='') tsNotice("都不能为空的哦!");
		
		$strTopic = $db->find("select * from ".dbprefix."group_topics where topicid='".$topicid."'");
		
		$strGroup = $db->find("select userid from ".dbprefix."group where groupid='".$strTopic['groupid']."'");
		
		if($strTopic['userid']==$userid || $strGroup['userid']==$userid || $TS_USER['user']['isadmin']==1){
		
			$arrData = array(
				'typeid' => $typeid,
				'title' => $title,
				'content' => $content,
				'iscomment' => $iscomment,
			);
			
			//判断是否有图片和附件
			preg_match_all('/\[(photo)=(\d+)\]/is', $content, $isphoto);
			if($isphoto[2]){
				$arrData['isphoto'] = '1';
			}else{
				$arrData['isphoto'] = '0';
			}
			//判断附件
			preg_match_all('/\[(attach)=(\d+)\]/is', $content, $isattach);
			if($isattach[2]){
				$arrData['isattach'] = '1';
			}else{
				$arrData['isattach'] = '0';
			}
			
			$db->update('group_topics',$arrData,array('topicid'=>$topicid));

			header("Location: ".SITE_URL.tsurl('group','topic',array('id'=>$topicid)));
			
		}else{
			header("Location: ".SITE_URL);
			exit;
		}
		
		
		break;
		
	//收藏帖子
	case "topic_collect":
		
		$userid = intval($TS_USER['user']['userid']);
		
		$topicid = $_POST['topicid'];
		
		$strTopic = $db->find("select * from ".dbprefix."group_topics where topicid='".$topicid."'");
		
		$collectNum = $db->findCount('group_topics_collects',array(
			'userid'=>$userid,
			'topicid'=>$topicid,
		));
		
		
		
		if($userid == '0'){
			echo 0;
		}elseif($userid == $strTopic['userid']){
			echo 1;
		}elseif($collectNum > 0){
			echo 2;
		}else{
			
			$db->create('group_topics_collects',array(
				'userid'=>$userid,
				'topicid'=>$topicid,
				'addtime'=>time(),
			));
			
			echo 3;
		}
		
		break;
		
	//置顶帖子
	case "topic_istop":
	
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0){
			header("Location: ".SITE_URL.tsurl('user','login'));
			exit;
		}
		
		$topicid = intval($_GET['topicid']);
		
		$strTopic = $db->find("select userid,groupid,istop from ".dbprefix."group_topics where topicid='$topicid'");
		
		$istop = $strTopic['istop'];
		
		$istop == 0 ? $istop = 1 : $istop = 0;
		
		$strGroup = $db->find("select userid from ".dbprefix."group where groupid='".$strTopic['groupid']."'");
		
		if($userid!=$strGroup['userid'] || $TS_USER['user']['isadmin']==1){

			$db->update('group_topics',array(
				'istop'=>$istop,
			),array(
				'topicid'=>$topicid,
			));
			
			tsNotice("帖子置顶成功！");
		}else{
			tsNotice("非法操作！");
		}
		break;
		
	//隐藏显示帖子
	case "topic_isshow":
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0){
			header("Location: ".SITE_URL.tsurl('user','login'));
			exit;
		}
		
		$topicid =intval($_GET['topicid']);
		
		$strTopic = $db->find("select userid,groupid,isshow from ".dbprefix."group_topics where `topicid`='$topicid'");
		
		$strGroup = $db->find("select userid from ".dbprefix."group where `groupid`='".$strTopic['groupid']."'");
		
		$isshow = intval($strTopic['isshow']);
		
		$isshow == 0 ? $isshow = 1 : $isshow = 0;
		
		if($userid == $strGroup['userid'] || $TS_USER['user']['isadmin']==1){

			$db->update('group_topics',array(
				'isshow'=>$isshow,
			),array(
				'topicid'=>$topicid,
			));
			
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
		
		if($tagname != ''){
		
			if(strlen($tagname) > '32') tsNotice("TAG长度大于32个字节（不能超过16个汉字）");
			
			$tagcount = $db->findCount('tag',array(
				'tagname'=>$tagname,
			));
			
			if($tagcount == '0'){
				
				$tagid = $db->create('tag',array(
					'tagname'=>$tagname,
					'uptime'=>time(),
				));
				
				$tagIndexCount = $db->findCount('tag_topic_index',array(
					'topicid'=>$topicid,
					'tagid'=>$tagid,
				));
				
				
				if($tagIndexCount == '0'){
					$db->query("INSERT INTO ".dbprefix."tag_topic_index (`topicid`,`tagid`) VALUES ('".$topicid."','".$tagid."')");
					
					$db->create();
					
				}
				
				$tagIdCount = $db->findCount('tag_topic_index',array(
					'tagid'=>$tagid,
				));

				$db->update('tag',array(
					'count_topic'=>$tagIdCount,
					'uptime'=>time(),
				),array(
					'tagid'=>$tagid,
				));
				
				
			}else{
				
				$tagData = $db->find("select * from ".dbprefix."tag where tagname='".$tagname."'");
				
				$tagIndexCount = $db->findCount('tag_topic_index',array(
					'topicid'=>$topicid,
					'tagid'=>$tagData['tagid'],
				));
				
				
				if($tagIndexCount == '0'){
					$db->query("INSERT INTO ".dbprefix."tag_topic_index (`topicid`,`tagid`) VALUES ('".$topicid."','".$tagData['tagid']."')");
				}

				$db->update('tag',array(
					'count_topic'=>$tagIdCount,
					'uptime'=>time(),
				),array(
					'tagid'=>$tagData['tagid'],
				));
				
			}
			
			echo "<script language=JavaScript>parent.window.location.reload();</script>";
			
		}
		
		break;
		
	//帖子分类
	case "topic_type":
	
		$groupid = intval($_POST['groupid']);
		$typename = t($_POST['typename']);
		if($typename != '')
		  
		  $db->create('group_topics_type',array(
			'groupid'=>$groupid,
			'typename'=>$typename,
		  ));
		
		header("Location: ".SITE_URL.tsurl('group','edit',array('groupid'=>$groupid,'ts'=>'type')));
		
		break;
			
	//回复评论
	case "recomment":
	
		$userid = intval($TS_USER['user']['userid']);
		
		$referid = $_POST['referid'];
		$topicid = $_POST['topicid'];
		$content = trim($_POST['content']);
		
		$db->create('group_topics_comments',array(
			'referid'=>$referid,
			'topicid'=>$topicid,
			'userid'=>$userid,
			'content'=>$content,
			'addtime'=>time(),
		));
		
		//统计评论数
		$count_comment = $db->findCount('group_topics_comments',array(
			'topicid'=>$topicid,
		));
		
		//更新帖子最后回应时间和评论数
		$db->update('group_topics',array(
			'uptime'=>time(),
			'count_comment'=>$count_comment,
		),array(
			'topicid'=>$topicid,
		));
		
		$strTopic = $db->find("select * from ".dbprefix."group_topics where topicid='$topicid'");
		$strComment = $db->find("select * from ".dbprefix."group_topics_comments where commentid='$referid'");
		
		if($topicid && $strTopic['userid'] != $TS_USER['user']['userid']){
			$msg_userid = '0';
			$msg_touserid = $strTopic['userid'];
			$msg_content = '你的帖子：《'.$strTopic['title'].'》新增一条评论，快去看看给个回复吧^_^ <br />'.SITE_URL.tsurl('group','topic',array('id'=>$topicid));
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		}
		
		if($referid && $strComment['userid'] != $TS_USER['user']['userid']){
			$msg_userid = '0';
			$msg_touserid = $strComment['userid'];
			$msg_content = '有人评论了你在帖子：《'.$strTopic['title'].'》中的回复，快去看看给个回复吧^_^ <br />'.SITE_URL.tsurl('group','topic',array('id'=>$topicid));
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

			$db->update('group_topics_type',array(
				'typename'=>$typename,
			),array(
				'typeid'=>$typeid,
			));
			
			echo $typename;
		}
		break;
	//删除帖子类型 
	case "del_type":
		$typeid = $_POST['typeid'];
		$db->query("delete from ".dbprefix."group_topics_type where typeid='$typeid'");

		$db->update('group_topics',array(
			'typeid'=>0,
		),array(
			'typeid'=>$typeid,
		));
		
		echo '0';
		break;
		
	//取消小组分类
	case "cate_cancel":
		$cateid = intval($_POST['cateid']);
		$groupid = $_POST['groupid'];
		$db->query("delete from ".dbprefix."group_cates_index where groupid='$groupid' and cateid='$cateid'");
		
		if($cateid > 0){
			
			//更新分类下小组数
			$groupnum = $db->findCount('group_cates_index',array(
				'cateid'=>$cateid,
			));
			
			$db->update('group_cates',array(
				'count_group'=>$groupnum,
				'uptime'=>$uptime,
			),array(
				'cateid'=>$cateid,
			));
			
			//判断是否有顶级分类
			$strCate = $db->find("select catereferid from ".dbprefix."group_cates where cateid='$cateid'");
			$catereferid = $strCate['catereferid'];
			
			if($catereferid > 0){
				//统计顶级分类下小组数
				$grouptotal = $db->find("select sum(`count_group`) as `total` from ".dbprefix."group_cates where catereferid='$catereferid'");
				
				$total = $grouptotal['total'];
				
				$db->update('group_cates',array(
					'count_group'=>$total,
					'uptime'=>$uptime,
				),array(
					'cateid'=>$catereferid,
				));
			}
		}
		
		echo '0';
		break;
			
	//计算帖子中是否有图片
	case "isphoto":
		$arrTopic = $db->findAll("select * from ".dbprefix."group_topics");
		foreach($arrTopic as $item){
			$content = $item['content'];
			$topicid = $item['topicid'];
			preg_match_all('/\[(photo)=(\d+)\]/is', $content, $photo);
			if($photo[2]){

				$db->update('group_topics',array(
					'isphoto'=>1,
				),array(
					'topicid'=>$topicid,
				));
				
				echo '==OK==';
			}
		}
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
		$groupid = $_POST['groupid'];
		$topicid = $_POST['topicid'];

		$db->update('group_topics',array(
			'groupid'=>$groupid,
		),array(
			'topicid'=>$topicid,
		));
		
		header("Location: ".SITE_URL.tsurl('group','topic',array('id'=>$topicid)));
		
		break;
		
	//置顶帖子 
	case "isposts":
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0){
			header("Location: ".SITE_URL.tsurl('user','login'));
			exit;
		}
		$topicid = intval($_GET['topicid']);
		
		if($userid == 0 || $topicid == 0) tsNotice("非法操作"); 
		
		$strTopic = $db->find("select userid,groupid,title,isposts from ".dbprefix."group_topics where topicid='$topicid'");
		
		$strGroup = $db->find("select userid from ".dbprefix."group where groupid='".$strTopic['groupid']."'");
		
		if($userid == $strGroup['userid'] || intval($TS_USER['user']['isadmin']) == 1){
			if($strTopic['isposts']==0){
				
				$db->update('group_topics',array(
					'isposts'=>1,
				),array(
					'topicid'=>$topicid,
				));
				
				//msg start
				$msg_userid = '0';
				$msg_touserid = $strTopic['userid'];
				$msg_content = '恭喜，你的帖子：《'.$strTopic['title'].'》被评为精华帖啦^_^ <br />'.SITE_URL.tsurl('group','topic',array('id'=>$topicid));
				aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
				//msg end
				
			}else{

				$db->update('group_topics',array(
					'isposts'=>0,
				),array(
					'topicid'=>$topicid,
				));
				
			}
			
			tsNotice("操作成功！");
		}else{
			tsNotice("非法操作！");
		}
		
		break;
		
}
