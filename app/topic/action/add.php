<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

// 用户是否登录
$userid = aac ( 'user' )->isLogin ();

//判断用户是否存在
if(aac('user')->isUser($userid)==false) tsNotice('不好意思，用户不存在！');

//判断发布者状态
if(aac('user')->isPublisher()==false) tsNotice('不好意思，你还没有权限发布内容！');

//发布时间限制
if(aac('system')->pubTime()==false) tsNotice('不好意思，当前时间不允许发布内容！');

//发布时间间隔限制
if($TS_SITE['timeblank']){
	$lastTopic = $new['topic']->find('topic',array(
		'userid'=>$userid,
	),'topicid,addtime','addtime desc');
	if($lastTopic){
		if((time()-$lastTopic['addtime'])<$TS_SITE['timeblank']){
			tsNotice('不好意思，您的内容发送频率过高！请等等再发布！');
		}
	}
}

//发布内容扣除积分限制
$strScoreOption = $new['topic']->find('user_score',array(
	'app'=>'topic',
	'action'=>'add',
	'ts'=>'do',
));
if($strScoreOption && $strScoreOption['status']==1){
	#用户积分数
	$strUserScore = $new['topic']->find('user_info',array(
		'userid'=>$userid,
	),'count_score');
	if($strUserScore['count_score']<$strScoreOption['score']){
		tsNotice('不好意思，您的积分不足！');
	}
}

switch ($ts) {
	// 发布帖子
	case "" :
		
		$groupid = tsIntval ( $_GET ['groupid'] );
		// 小组数目
		$groupNum = $new ['topic']->findCount ( 'group', array (
				'groupid' => $groupid 
		) );
		
		if ($groupNum == 0) {
			header ( "Location: " . SITE_URL );
			exit ();
		}
		
		// 小组会员
		$isGroupUser = $new ['topic']->findCount ( 'group_user', array (
				'userid' => $userid,
				'groupid' => $groupid 
		) );



		//小组信息
		$strGroup = $new ['topic']->find ( 'group', array (
            'groupid' => $groupid
		));
		$strGroup ['groupname'] = tsTitle( $strGroup ['groupname'] );
		$strGroup ['groupdesc'] = tsTitle( $strGroup ['groupdesc'] );



		if ($strGroup ['isaudit'] == 1) {
			tsNotice ( '小组还未审核通过，不允许发帖！' );
		}
		
		// 允许小组成员发帖
		if ($strGroup ['ispost'] == 0 && $isGroupUser == 0 && $userid != $strGroup ['userid']) {
			tsNotice ( "本小组只允许小组成员发贴，请加入小组后再发帖！" );
		}
		// 不允许小组成员发帖
		if ($strGroup ['ispost'] == 1 && $userid != $strGroup ['userid']) {
			tsNotice ( "本小组只允许小组组长发帖！" );
		}
		// 帖子类型
		$arrGroupType = $new ['topic']->findAll ( 'topic_type', array (
				'groupid' => $strGroup ['groupid'] 
		) );



		#加载草稿箱
        $strDraft = $new['topic']->find('draft',array(
            'userid'=>$userid,
            'types'=>'topic',
        ));


		
		$title = '发布帖子';
		// 包含模版
		include template ( "add" );
		
		break;
	
	// 执行发布帖子
	case "do" :
		
		#验证码验证
		$authcode = strtolower ( $_POST ['authcode'] );
		if ($TS_SITE['isauthcode']) {
			if ($authcode != $_SESSION ['verify']) {
				tsNotice ( "验证码输入有误，请重新输入！" );
			}
		}

		#人机验证
		$vaptcha_token = trim ( $_POST ['vaptcha_token'] );
		if ($TS_SITE['is_vaptcha']) {
			$strVt = vaptcha($vaptcha_token);
			if($strVt['success']==0) {
				tsNotice('人机验证未通过！');
			}
		}

		$groupid = tsIntval ( $_POST ['groupid'] );
		$title = trim( $_POST ['title'] );
		
		$content =  tsClean( $_POST ['content'] );
		$content2 =  emptyText($_POST ['content']);

		$typeid = tsIntval ( $_POST ['typeid'] );
		$tag = $_POST ['tag'];

		$score = tsIntval($_POST ['score']);#积分

		// 判断一下Title是否重复
		$isTitle = $new ['topic']->findCount ( 'topic', array (
		    'title' => $title
		) );
		
		if ($isTitle > 0) {
			tsNotice ( '有重复标题出现哦^_^' );
		}
		
		// 小组
		$strGroup = $new ['topic']->find ( 'group', array (
				'groupid' => $groupid 
		) );
		
		if ($strGroup ['isaudit'] == 1) {
			tsNotice ( '小组还未审核通过，不允许发帖！' );
		}

		if ($TS_USER ['isadmin'] == 0) {
			$title = antiWord ( $title );
			$content = antiWord ( $content );
			$tag = antiWord ( $tag );
		}
		
		$iscomment = tsIntval ( $_POST ['iscomment'] );
		$iscommentshow = tsIntval ( $_POST ['iscommentshow'] );
		
		// 帖子是否需要审核
		if ($strGroup ['ispostaudit'] == 1) {
			$isaudit = 1;
		} else {
			$isaudit = 0;
		}

		#应用后台设置发帖是否需要审核，只针对普通用户
		if($TS_APP['topicisaudit']==1 && $TS_USER['isadmin']==0){
			$isaudit = 1;
		}

		
		if ($title == '' || $content2 == '' || $content=='') {
			tsNotice ( '没有任何内容是不允许你通过滴^_^' );
		}

		if($score<0){
            tsNotice ( '积分填写有误！' );
        }
		
		/**
		 * ******************
		 */
		// 防止用户发布重复内容，调出用户上一次发表的内容
		$strPreTopic = $new ['topic']->find ( 'topic', array (
				'userid' => $userid 
		), 'topicid,title,addtime', 'addtime desc' );
		
		// print_r($strPreTopic);exit;


		// 发帖间隔时间
        /*
		$IntervalTime = time () - $strPreTopic ['addtime'];
		// if($strPreTopic && $IntervalTime<3600){
		if ($strPreTopic) {
			similar_text ( $strPreTopic ['title'], $title, $percent );
			if ($percent >= 90) {
				$new ['topic']->update ( 'topic', array (
						'topicid' => $strPreTopic ['topicid'] 
				), array (
						'isaudit' => 1 
				) );
				$isaudit = 1;
			}
		}
        */



		/**
		 * *****************
		 */

        $gaiyao = cututf8(t(tsDecode($content)),0,100);

		
		$topicid = $new ['topic']->create ( 'topic', array (
			'groupid' => $groupid,
			'typeid' => $typeid,
			'userid' => $userid,
			'title' => $title,
			'content' => $content,
			'gaiyao'=>$gaiyao,
			'score'=>$score,
			'iscomment' => $iscomment,
			'iscommentshow' => $iscommentshow,
			'isaudit' => $isaudit,
			'addtime' => time (),
			'uptime' => time () 
		) );


		#清空草稿箱
        $new['topic']->delete('draft',array(
            'userid'=>$userid,
            'types'=>'topic',
        ));

		
		// 统计用户发帖数
		$countUserTopic = $new ['topic']->findCount ( 'topic', array (
				'userid' => $userid 
		) );
		
		$new ['topic']->update ( 'user_info', array (
				'userid' => $userid 
		), array (
				'count_topic' => $countUserTopic 
		) );
		
		// 处理@用户名
		/*
		if (preg_match_all ( '/@/', $content, $at )) {
			preg_match_all ( "/@(.+?)([\s|:]|$)/is", $content, $matches );
			
			$unames = $matches [1];
			
			$ns = "'" . implode ( "','", $unames ) . "'";
			
			$csql = "username IN($ns)";
			
			if ($unames) {
				
				$query = $db->fetch_all_assoc ( "select userid,username from " . dbprefix . "user_info where $csql" );
				
				foreach ( $query as $v ) {
					$content = str_replace ( '@' . $v ['username'] . '', '[@' . $v ['username'] . ':' . $v ['userid'] . ']', $content );
					$msg_content = '我在帖子中提到了你<br />去看看：' . tsUrl ( 'group', 'topic', array (
							'id' => $topicid 
					) );
					aac ( 'message' )->sendmsg ( $userid, $v ['userid'], $msg_content );
				}
				$new ['topic']->update ( 'topic', array (
						'topicid' => $topicid 
				), array (
						'content' => $content 
				) );
			}
		}
		*/
		
		// 统计帖子类型
		if ($typeid) {
			$topicTypeNum = $new ['topic']->findCount ( 'topic', array (
					'typeid' => $typeid 
			) );
			
			$new ['topic']->update ( 'topic_type', array (
					'typeid' => $typeid 
			), array (
					'count_topic' => $topicTypeNum 
			) );
		}
		// 处理标签
		aac ( 'tag' )->addTag ( 'topic', 'topicid', $topicid, $tag );
		
		// 统计需要审核的帖子
		$count_topic_audit = $new ['topic']->findCount ( 'topic', array (
				'groupid' => $groupid,
				'isaudit' => '1' 
		) );
		
		// 统计小组下帖子数并更新
		$count_topic = $new ['topic']->findCount ( 'topic', array (
				'groupid' => $groupid 
		) );
		
		// 统计今天发布帖子数
		$today_start = strtotime ( date ( 'Y-m-d 00:00:00' ) );
		$today_end = strtotime ( date ( 'Y-m-d 23:59:59' ) );
		
		$count_topic_today = $new ['topic']->findCount ( 'topic', "`groupid`='$groupid' and `addtime`>'$today_start' and `addtime`<'$today_end'" );
		
		$new ['topic']->update ( 'group', array (
				'groupid' => $groupid 
		), array (
				'count_topic' => $count_topic,
				'count_topic_audit' => $count_topic_audit,
				'count_topic_today' => $count_topic_today,
				'uptime' => time () 
		) );
		
		// 对积分进行处理
		aac ( 'user' )->doScore ( $TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'] );

		#用户记录
		aac('pubs')->addLogs('topic','topicid',$topicid,$userid,$title,$content,0);

		
		header ( "Location: " . tsUrl('topic', 'show', array ('id' => $topicid)));
		break;
} 
