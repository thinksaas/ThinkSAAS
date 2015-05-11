<?php   
defined('IN_TS') or die('Access Denied.');
$userid = aac('user')->isLogin();
switch($ts){

	case "":
	
		$title = '创建资料库';
		include template('create');
	
		break;
		
	case "do":
	
		$title = trim($_POST['title']);
		$content = tsClean($_POST['content']);
		
		if (intval ( $TS_USER ['isadmin'] ) == 0) {
			// 过滤内容开始
			aac ( 'system' )->antiWord ( $title );
			aac ( 'system' )->antiWord ( $content );
			// 过滤内容结束
		}
		
		//1审核后显示0不审核
		if ($TS_APP['isaudit']==1) {
			$isaudit = 1;
		} else {
			$isaudit = 0;
		}
		
		if($title && $content){
		
			$albumid = $new['attach']->create('attach_album',array(
				'userid'=>$userid,
				'title'=>$title,
				'content'=>$content,
				'isaudit'=>$isaudit,
				'addtime'=>date('Y-m-d H:i:s'),
				'uptime'=>date('Y-m-d H:i:s'),
			));
			
			header('Location: '.tsUrl('attach','upload',array('albumid'=>$albumid)));
		
		}else{
			
			tsNotice('资料库信息填写不完整');
			
		}
	
		break;

}