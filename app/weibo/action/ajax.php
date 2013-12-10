<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
		
	case "add":
		
		$userid = intval($TS_USER['user']['userid']);

		if($userid==0){
			echo 0;exit;
		}

		$content = t($_POST['content']);

		if($content == ''){
			echo 1;exit;
		}
		
		if($TS_USER['user']['isadmin']==0){
			//过滤内容开始
			aac('system')->antiWord($content);
			//过滤内容结束
			//$isaudit = 1;
		}
			
		$weiboid = $new['weibo']->create('weibo',array(
			'userid'=>$userid,
			'content'=>$content,
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
		
		echo 2;exit;
		
		break;
		
	case "list":
	
		$arrWeibo = aac('weibo')->findAll('weibo',null,'addtime desc',null,10);
		foreach($arrWeibo as $key=>$item){
			$arrWeibo[$key]['user'] = aac('user')->getOneUser($item['userid']);
		}
		
		include template('ajax_list');
	
		break;
	
}