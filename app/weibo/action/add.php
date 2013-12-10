<?php 
defined('IN_TS') or die('Access Denied.');
//用户是否登录
$userid = aac('user')->isLogin();

switch($ts){
		
	case "do":
	
		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}
	
		$content = t($_POST['content']);
		
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
			'content'=>$content,
			'isaudit'=>$isaudit,
			'addtime'=>date('Y-m-d H:i:s'),
			'uptime'=>date('Y-m-d H:i:s'),
		));
		

		//feed开始
		$feed_template = '<span class="pl">说：</span><div class="quote"><span class="inq">{content}</span> <span><a class="j a_saying_reply" href="{link}" rev="unfold">回应</a></span></div>';
		$feed_data = array(
			'link'	=> tsurl('weibo','show',array('id'=>$weiboid)),
			'content'	=> cututf8(t($content),'0','50'),
		);
		aac('feed')->add($userid,$feed_template,$feed_data);
		//feed结束
		
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