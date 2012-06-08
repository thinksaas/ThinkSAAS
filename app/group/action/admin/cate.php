<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 小组分类管理
 */

switch($ts){
	//分类列表 
	case "list":
		
		$arrCate	= $new['group']->findAll('group_cates');

		include template("admin/cate_list");
		
		break;
	
	//验证回答
	
	case "veri":
		
		$arrCate	= $new['group']->findAll('veri');

		include template("admin/cate_veri");
		
		break;
	
	//分类添加
	case "add":

		include template("admin/cate_add");
		
		break;
		
	case "add_do":
		$arrData = array(
			'catename'	=> trim($_POST['catename']),
		);
		$cateid = $db->insertArr($arrData,dbprefix.'group_cates');
		header("Location: ".SITE_URL."index.php?app=group&ac=admin&mg=cate&ts=list");
		break;
	
	//分类删除
	case "del":
		
		$cateid = intval($_GET['cateid']);

		$groupNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."group where `cateid`='$cateid'");
		
		if($groupNum['count(*)'] > 0){
			qiMsg("此分类有小组存在，不允许删除！");
		}
		
		$db->query("delete from ".dbprefix."group_cates where cateid='$cateid'");
		
		
		qiMsg("分类删除成功！");
		
		break;
	
	//问题删除
	
	
	case "delveri":
		
		$vid = intval($_GET['verid']);

		$groupNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."veri where `verid`='$vid'");
		
		if(!$groupNum['count(*)']){
			qiMsg("不存在此问题");
		}
		
		$db->query("delete from ".dbprefix."veri where verid='$vid'");
		
		
		qiMsg("问题删除成功！");
		
		break;
	
	
	//分类修改
	case "edit":
		$cateid = $_GET['cateid'];
		$strCate = $db->once_fetch_assoc("select * from ".dbprefix."group_cates where cateid='$cateid'");

		include template("admin/cate_edit");
		
		break;
	
	//分类修改执行 
	case "edit_do":
		$cateid = $_POST['cateid'];
		$catename = $_POST['catename'];
		
		$db->query("update ".dbprefix."group_cates set `catename`='$catename' where cateid='$cateid'");
		
		header("Location: ".SITE_URL."index.php?app=group&ac=admin&mg=cate&ts=list");
		
		break;
}