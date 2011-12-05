<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "list":
		
		//列表 
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=article&ac=admin&mg=cate&ts=list&page=';
		$lstart = $page*10-10;

		$arrCate = $db->fetch_all_assoc("select * from ".dbprefix."article_cate order by cateid desc limit $lstart, 10");

		$cateNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."article_cate");

		$pageUrl = pagination($cateNum['count(*)'], 10, $page, $url);
		
		include template("admin/cate_list");
		
		break;
		
	case "add":
	
		include template("admin/cate_add");
	
		break;
		
	case "add_do":
	
		$catename = trim($_POST['catename']);
		
		$db->query("insert into ".dbprefix."article_cate (`catename`) values ('$catename')");
		
		header("Location: ".SITE_URL.'index.php?app=article&ac=admin&mg=cate&ts=list');
	
		break;
		
	case "edit":
		$cateid = $_GET['cateid'];
		
		$strCate = $db->once_fetch_assoc("select * from ".dbprefix."article_cate where `cateid`='$cateid'");
		
		include template("admin/cate_edit");
		break;
		
	case "edit_do":
		
		$cateid = $_POST['cateid'];
		$catename = trim($_POST['catename']);
		
		$db->query("update ".dbprefix."article_cate set `catename`='$catename' where `cateid`='$cateid'");
		
		qiMsg("修改成功！");
		
		break;
	
}