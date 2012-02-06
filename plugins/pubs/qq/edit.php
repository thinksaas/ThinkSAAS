<?php 
//插件编辑
switch($ts){
	case "set":
		$arrData = fileRead('data.php','plugins','pubs','qq');
		
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
		
		fileWrite('data.php','plugins/pubs/qq',$arrData);
		
		qiMsg("修改成功！");
		break;
}