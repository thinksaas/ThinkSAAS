<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "list":
		$modelid = intval($_GET['modelid']);
		
		$arrVirtue = $db->findAll('apple_virtue','modelid'=$modelid);
		
		include template('admin/virtue_list');
		break;
		
	//添加
	case "add":
		$modelid = intval($_GET['modelid']);
		
		include template('admin/virtue_add');
		break;
		
	//执行 
	case "add_do":
		$modelid = intval($_POST['modelid']);
		
		$virtuename = trim($_POST['virtuename']);
		
		$db->create('apple_virtue',array(
			'modelid'=>$modelid,
			'virtuename'=>$virtuename,
		));
		
		header("Location: ".SITE_URL.'index.php?app=apple&ac=admin&mg=virtue&ts=list&modelid='.$modelid);
		break;
	
	//编辑 
	case "edit":
		$virtueid = intval($_GET['virtueid']);
		
		$strVirtue = $db->find('apple_virtue',array('virtueid'=>$virtueid));
		
		include template('admin/virtue_edit');
		
		break;
		
	case "edit_do":
		$virtueid = intval($_POST['virtueid']);
		$virtuename = trim($_POST['virtuename']);
		
		$db->update('apple_virtue',array(
			'virtuename'=>$virtuename,
		),array(
			'virtueid'=>$virtueid
		));
		
		qiMsg("修改成功！");
		break;
		
	//删除 
	case "del":
		$virtueid = intval($_GET['virtueid']);
		
		$indexNum = $db->find("select count(*) from ".dbprefix."apple_index where `virtueid`='$virtueid'");
		
		if($indexNum['count(*)'] > 0){
			qiMsg("此属性有数据！不允许删除！");
		}
		
		$db->query("delete from ".dbprefix."apple_virtue where `virtueid`='$virtueid'");
		
		qiMsg("删除成功！");
		
		break;
}