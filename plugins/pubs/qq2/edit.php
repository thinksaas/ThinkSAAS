<?php
defined('IN_TS') or die('Access Denied.');
//插件编辑
switch($ts){
	case "set":
		$arrData = fileRead('plugins/pubs/qq2/data.php');
		
		include 'edit_set.html';
		break;
		
	case "do":
		$appid = trim($_POST['appid']);
		$appkey = trim($_POST['appkey']);
		$siteurl = $_POST['siteurl'];
		
		$arrData = array(
			'appid' => $appid,
			'appkey'	=>$appkey,
			'siteurl'	=> $siteurl,
		);
		
		fileWrite('data.php','plugins/pubs/qq2',$arrData);
		
		qiMsg("修改成功！");
		break;
}