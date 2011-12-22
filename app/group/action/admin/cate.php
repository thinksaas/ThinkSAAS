<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 小组分类管理
 */

switch($ts){
	//分类列表 
	case "list":
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = 'index.php?app=group&ac=admin&mg=cate&ts=list&page=';
		$arrCates	= $new['group']->getArrCate($page,10);
		$cateNum = $new['group']->getCateNum();
		$pageUrl = pagination($cateNum, 10, $page, $url);

		include template("admin/cate_list");
		
		break;
	
	//分类添加
	case "add":
		//调出顶级分类
		$topCates = $db->fetch_all_assoc("SELECT * FROM ".dbprefix."group_cates WHERE catereferid = '0'");

		include template("admin/cate_add");
		
		break;
		
	case "add_do":
		$arrData = array(
			'catename'	=> trim($_POST['catename']),
			'catereferid'	=> trim($_POST['catereferid']),
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
		$db->query("delete from ".dbprefix."group_cates where catereferid='$cateid'");
		$db->query("delete from ".dbprefix."group_cates_index where cateid='$cateid'");
		
		qiMsg("分类删除成功！");
		
		break;
	
	//分类修改
	case "edit":
		$cateid = $_GET['cateid'];
		$strCate = $db->once_fetch_assoc("select * from ".dbprefix."group_cates where cateid='$cateid'");
		
		//调出顶级分类
		$topCates = $db->fetch_all_assoc("SELECT * FROM ".dbprefix."group_cates WHERE catereferid = '0'");

		include template("admin/cate_edit");
		
		break;
	
	//分类修改执行 
	case "edit_do":
		$cateid = $_POST['cateid'];
		$catename = $_POST['catename'];
		$catereferid = $_POST['catereferid'];
		
		$db->query("update ".dbprefix."group_cates set `catename`='$catename',`catereferid`='$catereferid' where cateid='$cateid'");
		
		header("Location: ".SITE_URL."index.php?app=group&ac=admin&mg=cate&ts=list");
		
		break;
}