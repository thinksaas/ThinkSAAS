<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "list":
		
		//列表 
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=article&ac=admin&mg=cate&ts=list&page=';
		$lstart = $page*10-10;

		$arrCate = $db->findAll("select * from ".dbprefix."article_cate order by cateid desc limit $lstart, 10");

		$cateNum = $db->find("select count(*) from ".dbprefix."article_cate");

		$pageUrl = pagination($cateNum['count(*)'], 10, $page, $url);
		
		include template("admin/cate_list");
		
		break;
		
	case "add":
	
		include template("admin/cate_add");
	
		break;
		
	case "add_do":
	
		$catename = trim($_POST['catename']);
		
		$db->create('article_cate',array(
			'catename'=>$catename,
		));
		
		header("Location: ".SITE_URL.'index.php?app=article&ac=admin&mg=cate&ts=list');
	
		break;
		
	case "edit":
		$cateid = $_GET['cateid'];
		
		$strCate = $db->find("select * from ".dbprefix."article_cate where `cateid`='$cateid'");
		
		include template("admin/cate_edit");
		break;
		
	case "edit_do":
		
		$cateid = $_POST['cateid'];
		$catename = trim($_POST['catename']);
		
		$db->update('article_cate',array(
			'catename'=>$catename,
		),array(
			'cateid'=>$cateid,
		));
		
		qiMsg("修改成功！");
		
		break;
	
}