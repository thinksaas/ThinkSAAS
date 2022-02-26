<?php
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "":
		include 'userinfo.php';
		
		$page = tsIntval($_GET['page'],1);
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
				$arrUser[] =  $new['user']->getSimpleUser($item['touserid']);
			}
		}

		$title = $strUser['username'].'关注的人';
		include template("follow");
	
		break;
		
	//关注执行 
	case "do":
	
		$userid = tsIntval($TS_USER['userid']);
		$touserid = tsIntval($_POST['userid']);

		
		if($userid == 0){
			echo json_encode(array(
				'status'=>0,
				'msg'=>'你还没有登录！',
			));
			exit;
		}
		
		if($userid == $touserid){
			echo json_encode(array(
				'status'=>0,
				'msg'=>'自己不能关注自己哦',
			));
			exit;
		}
		
		$isFollow = $new['user']->findCount('user_follow',array(
			'userid'=>$userid,
			'touserid'=>$touserid,
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
			'touserid'=>$touserid,
		));
		
		//统计用户关注数和粉丝数
		$new['user']->countFollowFans($userid);
		$new['user']->countFollowFans($touserid);
		

		#发个消息


		echo json_encode(array(
			'status'=>2,
			'msg'=>'关注成功！',
		));
		exit;
	
		break;
		
	//取消关注
	case "un":
	
		$userid = tsIntval($TS_USER['userid']);
		$touserid = tsIntval($_POST['userid']);

		
		if($userid == 0){
			echo json_encode(array(
				'status'=>0,
				'msg'=>'你还没有登录！',
			));
			exit;
		}
		
		$new['user']->delete('user_follow',array(
			'userid'=>$userid,
			'touserid'=>$touserid,
		));
		
		//统计用户关注数和粉丝数
		$new['user']->countFollowFans($userid);
		$new['user']->countFollowFans($touserid);
		
		echo json_encode(array(
			'status'=>1,
			'msg'=>'解除关注成功',
		));
		exit;
		
		break;
	
}