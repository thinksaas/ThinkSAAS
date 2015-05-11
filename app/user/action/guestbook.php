<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "":
	
		include 'userinfo.php';
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = tsUrl('user','guestbook',array('id'=>$strUser['userid'],'page'=>''));
		$lstart = $page*20-20;
		
		$arrGuestsList = $new['user']->findAll('user_gb',array(
			'touserid'=>$strUser['userid'],
		),'addtime desc',null,$lstart.',20');

		foreach($arrGuestsList as $key=>$item){
			$arrGuestList[] = $item;
			$arrGuestList[$key]['user']=$new['user']->getOneUser($item['userid']);
		}
		
		$guestNum = $new['user']->findCount('user_gb',array(
			'touserid'=>$strUser['userid'],
		));
		
		$pageUrl = pagination($guestNum, 20, $page, $url);
		
		$title = $strUser['username'].'的留言板';
		include template('guestbook');
	
		break;
		
	case "do":
		
		$userid = $new['user']->isLogin();
		$touserid = intval($_POST['touserid']);
		$content = tsClean($_POST['content']);
		
		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}
		
		if($content == ''){
		
			tsNotice('留言内容不能为空!');
		
		}
		
		aac('system')->antiWord($content);
		
		$new['user']->create('user_gb',array(
			'userid'=>$userid,
			'touserid'=>$touserid,
			'content'=>$content,
			'addtime'=>date('Y-m-d H:i:s'),
		));
		
		//发送系统消息
		$msg_userid = '0';
		$msg_touserid = $touserid;
		$msg_content = '有人在你的留言板上留言了哦，快去看看吧！<br />'.tsUrl('user','space',array('id'=>$touserid));
		aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		
		tsNotice('留言成功！');
		
		break;
		
	case "redo":
		$userid = $new['user']->isLogin();
		$touserid = intval($_POST['touserid']);
		$reid = intval($_POST['reid']);
		$content = tsClean($_POST['content']);
		
		$arrContent = explode('#',$content);
		
		$content = $arrContent['1'];
		
		if($content==''){
			tsNotice('留言不能为空！');
		}
		
		aac('system')->antiWord($content);
		
		$new['user']->create('user_gb',array(
			'userid'=>$userid,
			'reid'=>$reid,
			'touserid'=>$touserid,
			'content'=>$content,
			'addtime'=>date('Y-m-d H:i:s'),
		));
		
		//发送系统消息
		$msg_userid = '0';
		$msg_touserid = $touserid;
		$msg_content = '有人在你的留言板上留言了哦，快去看看吧！<br />'.tsUrl('user','space',array('id'=>$touserid));
		aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		tsNotice('回复成功！');
		break;
		
	//删除留言
	case "delete":
	
		$userid = $new['user']->isLogin();
		$gbid = intval($_GET['gbid']);
		
		$strGuest = $new['user']->find('user_gb',array(
			'id'=>$gbid,
		));
		
		if($strGuest['touserid'] == $userid){
		
			$new['user']->delete('user_gb',array(
				'id'=>$gbid,
			));
		
		}
		
		tsNotice('留言删除成功');
	
		break;
	
}