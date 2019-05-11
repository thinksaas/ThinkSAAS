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


switch ($ts) {
	// 发布帖子
	case "" :
		
		$groupid = intval ( $_GET ['id'] );
		// 小组数目
		$groupNum = $new ['group']->findCount ( 'group', array (
				'groupid' => $groupid 
		) );
		
		if ($groupNum == 0) {
			header ( "Location: " . SITE_URL );
			exit ();
		}
		
		// 小组会员
		$isGroupUser = $new ['group']->findCount ( 'group_user', array (
				'userid' => $userid,
				'groupid' => $groupid 
		) );



		//小组信息
		$strGroup = $new ['group']->find ( 'group', array (
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
		$arrGroupType = $new ['group']->findAll ( 'group_topic_type', array (
				'groupid' => $strGroup ['groupid'] 
		) );



		#加载草稿箱
        $strDraft = $new['group']->find('draft',array(
            'userid'=>$userid,
            'types'=>'topic',
        ));


		
		$title = '发布帖子';
		// 包含模版
		include template ( "add" );
		
		break;
	
	// 执行发布帖子
	case "do" :

		
		$authcode = strtolower ( $_POST ['authcode'] );
		
		if ($TS_SITE['isauthcode']) {
			if ($authcode != $_SESSION ['verify']) {
				tsNotice ( "验证码输入有误，请重新输入！" );
			}
		}
		
		$groupid = intval ( $_POST ['groupid'] );
		$title = trim( $_POST ['title'] );
		
		$content =  tsClean( $_POST ['content'] );
		$content2 =  emptyText( $_POST ['content'] );

		$typeid = intval ( $_POST ['typeid'] );
		$tag = $_POST ['tag'];
		
		// 判断一下Title是否重复
		$isTitle = $new ['group']->findCount ( 'group_topic', array (
				'title' => $title 
		) );
		
		if ($isTitle > 0) {
			tsNotice ( '有重复标题出现哦^_^' );
		}
		
		// 小组
		$strGroup = $new ['group']->find ( 'group', array (
				'groupid' => $groupid 
		) );
		
		if ($strGroup ['isaudit'] == 1) {
			tsNotice ( '小组还未审核通过，不允许发帖！' );
		}
		
		if ($TS_USER ['isadmin'] == 0) {
			aac ( 'system' )->antiWord ( $title );
			aac ( 'system' )->antiWord ( $content );
			aac ( 'system' )->antiWord ( $tag );
		}
		
		$iscomment = intval ( $_POST ['iscomment'] );
		$iscommentshow = intval ( $_POST ['iscommentshow'] );
		
		// 帖子是否需要审核
		if ($strGroup ['ispostaudit'] == 1) {
			$isaudit = 1;
		} else {
			$isaudit = 0;
		}
		
		if ($title == '' || $content2 == '') {
			tsNotice ( '没有任何内容是不允许你通过滴^_^' );
		}
		
		/**
		 * ******************
		 */
		// 防止用户发布重复内容，调出用户上一次发表的内容
		$strPreTopic = $new ['group']->find ( 'group_topic', array (
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
				$new ['group']->update ( 'group_topic', array (
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

		
		$topicid = $new ['group']->create ( 'group_topic', array (
				'groupid' => $groupid,
				'typeid' => $typeid,
				'userid' => $userid,
				'locationid'=>aac('user')->getLocationId($userid),
				'title' => $title,
				'content' => $content,
				'gaiyao'=>$gaiyao,
				'iscomment' => $iscomment,
				'iscommentshow' => $iscommentshow,
				'isaudit' => $isaudit,
				'addtime' => time (),
				'uptime' => time () 
		) );


		#清空草稿箱
        $new['group']->delete('draft',array(
            'userid'=>$userid,
            'types'=>'topic',
        ));

		
		// 统计用户发帖数
		$countUserTopic = $new ['group']->findCount ( 'group_topic', array (
				'userid' => $userid 
		) );
		
		$new ['group']->update ( 'user_info', array (
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
				$new ['group']->update ( 'group_topic', array (
						'topicid' => $topicid 
				), array (
						'content' => $content 
				) );
			}
		}
		*/
		
		// 统计帖子类型
		if ($typeid) {
			$topicTypeNum = $new ['group']->findCount ( 'group_topic', array (
					'typeid' => $typeid 
			) );
			
			$new ['group']->update ( 'group_topic_type', array (
					'typeid' => $typeid 
			), array (
					'count_topic' => $topicTypeNum 
			) );
		}
		// 处理标签
		aac ( 'tag' )->addTag ( 'topic', 'topicid', $topicid, $tag );
		
		// 统计需要审核的帖子
		$count_topic_audit = $new ['group']->findCount ( 'group_topic', array (
				'groupid' => $groupid,
				'isaudit' => '1' 
		) );
		
		// 统计小组下帖子数并更新
		$count_topic = $new ['group']->findCount ( 'group_topic', array (
				'groupid' => $groupid 
		) );
		
		// 统计今天发布帖子数
		$today_start = strtotime ( date ( 'Y-m-d 00:00:00' ) );
		$today_end = strtotime ( date ( 'Y-m-d 23:59:59' ) );
		
		$count_topic_today = $new ['group']->findCount ( 'group_topic', "`groupid`='$groupid' and `addtime`>'$today_start' and `addtime`<'$today_end'" );
		
		$new ['group']->update ( 'group', array (
				'groupid' => $groupid 
		), array (
				'count_topic' => $count_topic,
				'count_topic_audit' => $count_topic_audit,
				'count_topic_today' => $count_topic_today,
				'uptime' => time () 
		) );
		
		// 对积分进行处理
		aac ( 'user' )->doScore ( $TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'] );

		
		header ( "Location: " . tsUrl('group', 'topic', array ('id' => $topicid)));
		break;
} 
