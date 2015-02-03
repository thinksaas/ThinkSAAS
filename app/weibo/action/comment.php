<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "":

		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}

		//用户是否登录
		$userid = aac('user')->isLogin();
		$weiboid = intval($_POST['weiboid']);
		$touserid = intval($_POST['touserid']);
		$content = tsClean($_POST['content']);

		if($content == ''){
			tsNotice('内容不能为空');
		}

		if($TS_USER['user']['isadmin']==0){
			//过滤内容开始
			aac('system')->antiWord($content);
			//过滤内容结束
		}

		$commentid = $new['weibo']->create('weibo_comment',array(
			'userid'=>$userid,
			'touserid'=>$touserid,
			'weiboid'=>$weiboid,
			'content'=>$content,
			'addtime'=>date('Y-m-d H:i:s'),
		));

		//计算评论总数
		$commentNum = $new['weibo']->findCount('weibo_comment',array(
			'weiboid'=>$weiboid,
		));

		$new['weibo']->update('weibo',array(
			'weiboid'=>$weiboid,
		),array(
			'count_comment'=>$commentNum,
		));
		
		$strWeibo = $new['weibo']->find('weibo',array(
			'weiboid'=>$weiboid,
		));
		
		if($strWeibo['userid'] != $userid){
			$msg_userid = '0';
			$msg_touserid = $strWeibo['userid'];
			$msg_content = '你的微博新增一条回复，快去看看给个回复吧^_^ <br />'.tsUrl('weibo','show',array('id'=>$weiboid));
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		}

		header("Location: ".tsUrl('weibo','show',array('id'=>$weiboid)));
		
		break;
		
	//删除评论
	case "delete":
		$userid = aac('user')->isLogin();
	
		$commentid = intval($_GET['commentid']);
		
		$strComment = $new['weibo']->find('weibo_comment',array(
			'commentid'=>$commentid,
		));
	
		if($TS_USER['user']['isadmin']==1 || $strComment['userid']==$userid){
			
			
			$new['weibo']->delete('weibo_comment',array('commentid'=>$commentid));
			
			//统计
			$count_comment = $new['weibo']->findCount('weibo_comment',array(
				'weiboid'=>$strComment['weiboid'],
			));
			
			$new['weibo']->update('weibo',array(
				'weiboid'=>$strComment['weiboid'],
			),array(
				'count_comment'=>$count_comment,
			));
			
			header('Location: '.tsUrl('weibo','show',array('id'=>$strComment['weiboid'])));
			exit;
			
		}else{
			tsNotice('非法操作！');
		}
		break;

}