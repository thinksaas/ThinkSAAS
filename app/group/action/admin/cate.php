<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	//分类列表 
	case "list":
		
		$arrCate = $new['group']->findAll('group_cate',array(
			'referid'=>'0',
		));
		
		foreach($arrCate as $key=>$item){
		
			$arrCates[] = $item;
			$arrCates[$key]['two'] = $new['group']->findAll('group_cate',array(
				'referid'=>$item['cateid'],
			));
		
		}
		
		foreach($arrCates as $key=>$item){
		
			$arrCatess[] = $item;
			foreach($item['two'] as $tkey=>$titem){
			
				$arrCatess[$key]['two'][$tkey]['three'] = $new['group']->findAll('group_cate',array(
					'referid'=>$titem['cateid'],
				));
			
			}
		
		}
		
		//print_r($arrCatess);

		include template("admin/cate_list");
		
		break;
	
	//分类添加
	case "add":
		
		$referid = intval($_GET['referid']);

		include template("admin/cate_add");
		
		break;
		
	case "add_do":
		
		$new['group']->create('group_cate',array(
		
			'catename'=>t($_POST['catename']),
			'referid'=>intval($_POST['referid']),
		
		));
		
		
		header("Location: ".SITE_URL."index.php?app=group&ac=admin&mg=cate&ts=list");
		
		break;
	
	//分类删除
	case "del":
		
		$cateid = intval($_GET['cateid']);

		$groupNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."group where `cateid`='$cateid'");
		
		if($groupNum['count(*)'] > 0){
			qiMsg("此分类有小组存在，不允许删除！");
		}
		
		$db->query("delete from ".dbprefix."group_cate where cateid='$cateid'");
		
		
		qiMsg("分类删除成功！");
		
		break;
	
	//分类修改
	case "edit":
	
		$cateid = intval($_GET['cateid']);
		
		$referid = intval($_GET['referid']);
		
		$strCate = $db->once_fetch_assoc("select * from ".dbprefix."group_cate where cateid='$cateid'");
		
		//调出顶级分类
		if($referid){
			$arrOneCate = $new['group']->findAll('group_cate',array(
				'referid'=>0,
			));
		}

		include template("admin/cate_edit");
		
		break;
	
	//分类修改执行 
	case "edit_do":
		$cateid = intval($_POST['cateid']);
		$catename = t($_POST['catename']);
		
		$referid = intval($_POST['referid']);
		
		$refer = '';
		if($referid){
			$refer = ", `referid`='$referid'";
		}
		
		$db->query("update ".dbprefix."group_cate set `catename`='".$catename."'".$refer." where cateid='$cateid'");
		
		header("Location: ".SITE_URL."index.php?app=group&ac=admin&mg=cate&ts=list");
		
		break;
}