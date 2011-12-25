<?php
defined('IN_TS') or die('Access Denied.');
//插件编辑
switch($ts){
	case "set":
	
		$word = fileRead('data.php','plugins','pubs','wast_word');
		$word = stripslashes($word);
		
		include 'edit_set.html';
		break;
		
	case "do":
		$word = $_POST['word'];
		
		fileWrite('data.php','plugins/pubs/wast_word',$word);
		
		qiMsg("修改成功！");
		break;
}