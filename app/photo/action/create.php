<?php 
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

//判断发布者状态
if(aac('user')->isPublisher()==false) tsNotice('不好意思，你还没有权限发布内容！');

//发布时间限制
if(aac('system')->pubTime()==false) tsNotice('不好意思，当前时间不允许发布内容！');

switch($ts){


	case "":
		
		$title = '创建相册';
		include template("create");
	
		break;
		
	case "do":
		
		//用户是否登录
		$userid = aac('user')->isLogin();
		
		$albumname = trim($_POST['albumname']);
		$albumdesc = trim($_POST['albumdesc']);
		
		if($albumname == '') {
			tsNotice("相册名称不能为空！");
		}
		
		//1审核后显示0不审核
		if ($TS_APP['isaudit']==1) {
			$isaudit = 1;
		} else {
			$isaudit = 0;
		}
		
		if($TS_USER['isadmin']==0){
			//过滤内容开始
			aac('system')->antiWord($albumname);
			aac('system')->antiWord($albumdesc);
			//过滤内容结束
		}
		
		$albumid = $new['photo']->create('photo_album',array(
		
			'userid'=>$userid,
			'albumname'=>$albumname,
			'albumdesc'=>$albumdesc,
			'isaudit'=>$isaudit,
			'addtime'=>date('Y-m-d H:i:s'),
			'uptime'=>date('Y-m-d H:i:s'),
		
		));
		
		header("Location: ".tsUrl('photo','upload',array('albumid'=>$albumid)));
	
	
		break;


}