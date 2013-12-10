<?php   
defined('IN_TS') or die('Access Denied.');
$userid = aac('user')->isLogin();
switch($ts){

	case "":
	
		$title = '创建资料库';
		include template('create');
	
		break;
		
	case "do":
	
		$title = tsClean($_POST['title']);
		$content = tsClean($_POST['content']);
		
		if($title && $content){
		
			$albumid = $new['attach']->create('attach_album',array(
				'userid'=>$userid,
				'title'=>$title,
				'content'=>$content,
				'addtime'=>date('Y-m-d H:i:s'),
				'uptime'=>date('Y-m-d H:i:s'),
			));
			
			header('Location: '.tsUrl('attach','upload',array('albumid'=>$albumid)));
		
		}else{
			
			tsNotice('资料库信息填写不完整');
			
		}
	
		break;

}