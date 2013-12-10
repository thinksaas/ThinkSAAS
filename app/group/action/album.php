<?php 
defined('IN_TS') or die('Access Denied.');
//帖子专辑

switch($ts){

	//专辑
	case "":
		$albumid = intval($_GET['id']);
		$strAlbum = $new['group']->find('group_album',array(
			'albumid'=>$albumid,
		));
		
		$strAlbum['user'] = aac('user')->getOneUser($strAlbum['userid']);
		if($strAlbum['groupid']){
			$strAlbum['group'] = aac('group')->getOneGroup($strAlbum['groupid']);
		}
		
		//专辑帖子
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = tsUrl('group','album',array('id'=>$strAlbum['albumid'],'page'=>''));
		$lstart = $page*20-20;
		$arrAlbumTopic = $new['group']->findAll('group_album_topic',array(
			'albumid'=>$strAlbum['albumid'],
		),'addtime desc','topicid',$lstart.',20');
		
		$topicNum = $new['group']->findCount('group_album_topic',array(
			'albumid'=>$strAlbum['albumid'],
		));
		
		$pageUrl = pagination($topicNum, 20, $page, $url);
		
		foreach($arrAlbumTopic as $key=>$item){
			$arrTopics[] = $new['group']->find('group_topic',array(
				'topicid'=>$item['topicid'],
			));
		}
		
		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['group'] = $new['group']->getOneGroup($item['groupid']);
		}
	
		$title = $strAlbum['albumname'].'专辑';
		
			//当前位置
			$arrLocal = array(
				array(
					'title'=>'首页',
					'url'=>SITE_URL,
				),
				array(
					'title'=>'小组',
					'url'=>tsUrl('group'),
				),
				array(
					'title'=>'专辑',
					'url'=>tsUrl('group','album',array('ts'=>'list')),
				),
				array(
					'title'=>$strAlbum['albumname'],
					'url'=>tsUrl('group','album',array('id'=>$strAlbum['albumid'])),
				),
			);
		
		include template('album');
		break;
		
	//专辑列表
	case "list":
	
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = tsUrl('group','album',array('ts'=>'list','page'=>''));
		$lstart = $page*20-20;
		$arrAlbum = $new['group']->findAll('group_album',null,'addtime desc',null,$lstart.',20');
		$albumNum = $new['group']->findCount('group_album');
		$pageUrl = pagination($albumNum, 20, $page, $url);
	
		$title = '专辑';
		include template('album_list');
		break;
		
	//创建专辑
	case "add":
		$userid = aac('user')->isLogin();
		
		$myGroups = $new['group']->findAll('group_user',array(
			'userid'=>$userid,
		),null,'groupid');
		
		$arrGroup = '';
		
		foreach($myGroups as $key=>$item){
			$strGroup = $new['group']->getOneGroup($item['groupid']);
			if($strGroup['ispost']==0 && $strGroup['isaudit']==0){
				$arrGroup[] = $strGroup;
			}
		}
		
		$title = '创建专辑';
		include template('album_add');
		break;
		
	case "adddo":
	
		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}
	
		$userid = aac('user')->isLogin();
		
		$groupid = intval($_POST['groupid']);
		
		$albumname = t($_POST['albumname']);
		$albumdesc = tsClean($_POST['albumdesc']);
		
		if($albumname == '' || $albumdesc == ''){
			tsNotice('专辑名称或者专辑介绍不能为空');
		}
		
		if($TS_USER['user']['isadmin']==0){
			//过滤内容开始
			aac('system')->antiWord($albumname);
			aac('system')->antiWord($albumdesc);
			//过滤内容结束
		}
		
		$albumid = $new['group']->create('group_album',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
			'albumname'=>$albumname,
			'albumdesc'=>$albumdesc,
			'addtime'=>date('Y-m-d H:i:s'),
		));
		
		header('Location: '.tsUrl('group','album',array('id'=>$albumid)));
		
		break;
		
	//编辑专辑
	case "edit":
		$userid = aac('user')->isLogin();
		break;
		
	case "editdo":
		$userid = aac('user')->isLogin();
		break;
		
	//删除专辑
	case "delete":
		$userid = aac('user')->isLogin();
		break;
		
	//淘贴窗口
	case "topic":
		
		$userid = intval($TS_USER['user']['userid']);
		
		$topicid = intval($_POST['topicid']);
		
		if($userid == 0 || $topicid == 0){
			echo 0;exit;
		}
		
		$albumNum = $new['group']->findCount('group_album',array(
			'userid'=>$userid,
		));
		
		if($albumNum == 0){
			echo 1;exit;
		}
		
		$arrAlbum = $new['group']->findAll('group_album',array(
			'userid'=>$userid,
		));
		
		include template('album_topic');
		
		break;
		
	case "topicdo":
		$userid = aac('user')->isLogin();
		
		$albumid = intval($_POST['albumid']);
		$topicid = intval($_POST['topicid']);
		
		$topicNum = $new['group']->findCount('group_album_topic',array(
			'albumid'=>$albumid,
			'topicid'=>$topicid,
		));
		
		if($topicNum == 0){
			$new['group']->create('group_album_topic',array(
			
				'albumid'=>$albumid,
				'topicid'=>$topicid,
				'addtime'=>date('Y-m-d H:i:s'),
			
			));
			
			//更新
			$count_topic = $new['group']->findCount('group_album_topic',array(
				'albumid'=>$albumid,
			));
			
			$new['group']->update('group_album',array(
				'albumid'=>$albumid,
			),array(
				'count_topic'=>$count_topic,
			));
			
		}
		
		header('Location: '.tsUrl('group','album',array('id'=>$albumid)));
		
		break;
	

}