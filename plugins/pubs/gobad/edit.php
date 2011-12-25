<?php
defined('IN_TS') or die('Access Denied.');
//插件编辑
switch($ts){
	case "set":
		$code = fileRead('data.php','plugins','pubs','gobad');
		$code = stripslashes($code);
		
		include 'edit_set.html';
		break;
		
	case "do":
		$code = $_POST['code'];
		
		fileWrite('data.php','plugins/pubs/gobad',$code);
		
		qiMsg("修改成功！");
		break;
}