<?php
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "":
		include 'userinfo.php';
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = tsUrl('user','follow',array('id'=>$strUser['userid'],'page'=>''));
		$lstart = $page*80-80;
		
		//关注的用户
		$arrUsers = $new['user']->findAll('user_follow',array(
			'userid'=>$strUser['userid'],
		),'addtime desc',null,$lstart.',80');
		
		$userNum = $new['user']->findCount('user_follow',array(
			'userid'=>$strUser['userid'],
		));
		$pageUrl = pagination($userNum, 80, $page, $url);

		if(is_array($arrUsers)){
			foreach($arrUsers as $item){
				$arrUser[] =  $new['user']->getOneUser($item['userid_follow']);
			}
		}

		$title = $strUser['username'].'关注的人';
		include template("follow");
	
		break;
		
	//关注执行 
	case "do":
	
		$userid = intval($TS_USER['userid']);
		$userid_follow = intval($_GET['userid']);
		
		if($_GET['token'] != $_SESSION['token']) {
			echo json_encode(array(
				'status'=>0,
				'msg'=>'非法操作！',
			));
			exit;
		}
		
		if($userid == 0){
			echo json_encode(array(
				'status'=>0,
				'msg'=>'你还没有登录！',
			));
			exit;
		}
		
		if($userid == $userid_follow){
			echo json_encode(array(
				'status'=>0,
				'msg'=>'自己不能关注自己哦',
			));
			exit;
		}
		
		$isFollow = $new['user']->findCount('user_follow',array(
			'userid'=>$userid,
			'userid_follow'=>$userid_follow,
		));
		
		if($isFollow>0){
		
			echo json_encode(array(
				'status'=>1,
				'msg'=>'你已经关注此用户！',
			));
			exit;
		
		}
		
		$new['user']->create('user_follow',array(
			'userid'=>$userid,
			'userid_follow'=>$userid_follow,
		));
		
		//统计关注和被关注
		$strUser = $new['user']->find('user_info',array(
			'userid'=>$userid,
		));
		
		$followUser = $new['user']->find('user_info',array(
			'userid'=>$userid_follow,
		));
		
		$new['user']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'count_follow'=>$strUser['count_follow']+1,
		));
		
		$new['user']->update('user_info',array(
			'userid'=>$userid_follow,
		),array(
			'count_followed'=>$followUser['count_followed']+1,
		));
		//统计关注和被关注结束
		
		echo json_encode(array(
			'status'=>2,
			'msg'=>'关注成功！',
		));
		exit;
	
		break;
		
	//取消关注
	case "un":
	
		$userid = intval($TS_USER['userid']);
		$userid_follow = intval($_GET['userid']);
		
		if($_GET['token'] != $_SESSION['token']) {
			echo json_encode(array(
				'status'=>0,
				'msg'=>'非法操作！',
			));
			exit;
		}
		
		if($userid == 0){
			echo json_encode(array(
				'status'=>0,
				'msg'=>'你还没有登录！',
			));
			exit;
		}
		
		$new['user']->delete('user_follow',array(
			'userid'=>$userid,
			'userid_follow'=>$userid_follow,
		));
		
		//统计关注和被关注
		$strUser = $new['user']->find('user_info',array(
			'userid'=>$userid,
		));
		
		$followUser = $new['user']->find('user_info',array(
			'userid'=>$userid_follow,
		));
		
		$new['user']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'count_follow'=>$strUser['count_follow']-1,
		));
		
		$new['user']->update('user_info',array(
			'userid'=>$userid_follow,
		),array(
			'count_followed'=>$followUser['count_followed']-1,
		));
		//统计关注和被关注结束
		
		echo json_encode(array(
			'status'=>1,
			'msg'=>'解除关注成功',
		));
		exit;
		
		break;
	
}