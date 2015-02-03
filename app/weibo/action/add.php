<?php 
defined('IN_TS') or die('Access Denied.');
//用户是否登录
$userid = aac('user')->isLogin();

//无人值守状态下
if($TS_SITE['base']['isunattended']) tsNotice('客官，俺家主人说了，这个点暂不接收送来的任何帖子，请回吧！如需人工帮助，请直接点击客服留言咨询！');

switch($ts){
		
	case "do":
	
		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}
	
		$content = tsClean($_POST['content']);
		
		if($content == '') {
			tsNotice('内容不能为空');
		}
		
		$isaudit = 0;
		
		if($TS_USER['user']['isadmin']==0){
			//过滤内容开始
			aac('system')->antiWord($content);
			//过滤内容结束
		}
			
		$weiboid = $new['weibo']->create('weibo',array(
			'userid'=>$userid,
			'locationid'=>aac('user')->getLocationId($userid),
			'content'=>$content,
			'isaudit'=>$isaudit,
			'addtime'=>date('Y-m-d H:i:s'),
			'uptime'=>date('Y-m-d H:i:s'),
		));
		
		//对积分进行处理
		aac('user')->doScore($app,$ac,$ts);
		
		//QQ分享
		$arrShare = array(
			'content'=>$content.'[ThinkSAAS社区]'.tsurl('weibo','show',array('id'=>$weiboid)),
		);
		doAction('qq_share',$arrShare);
		//微博分享
		doAction('weibo_share',$content.'[ThinkSAAS社区]'.tsurl('weibo','show',array('id'=>$weiboid)));
		
		header("Location: ".tsurl('weibo','show',array('id'=>$weiboid)));
		exit;
	
		break;
	
}