<?php   
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

//判断发布者状态
if(aac('user')->isPublisher()==false) tsNotice('不好意思，你还没有权限发布内容！');

switch($ts){

	case "":

        if($TS_APP['ispost'] == 0 || $TS_USER['isadmin']==1){

            $title = '创建资料库';
            include template('create');

        }else{

            tsNotice('系统不允许普通用户创建资料库！');

        }
	

	
		break;
		
	case "do":
	
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		
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