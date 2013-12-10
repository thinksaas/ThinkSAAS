<?php 
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

switch($ts){


	case "":
		
		$title = '创建相册';
		include template("create");
	
		break;
		
	case "do":
	
	
		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}
		
		//用户是否登录
		$userid = aac('user')->isLogin();
		
		$albumname = t($_POST['albumname']);
		if($albumname == '') {
			tsNotice("相册名称不能为空！");
		}
		$albumdesc = t($_POST['albumdesc']);
		
		
		if($TS_USER['user']['isadmin']==0){
			//过滤内容开始
			aac('system')->antiWord($albumname);
			aac('system')->antiWord($albumdesc);
			//过滤内容结束
		}
		
		$albumid = $new['photo']->create('photo_album',array(
		
			'userid'=>$userid,
			'albumname'=>$albumname,
			'albumdesc'=>$albumdesc,
			'addtime'=>date('Y-m-d H:i:s'),
			'uptime'=>date('Y-m-d H:i:s'),
		
		));
		
		header("Location: ".tsUrl('photo','upload',array('albumid'=>$albumid)));
	
	
		break;


}