<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "":
		
		break;

	case "do":
		
		$groupid = intval($_POST['groupid']);
		$cateid = intval($_POST['cateid']);
		
		$new['group']->update('group',array(
			'groupid'=>$groupid,
		),array(
			'cateid'=>$cateid,
		));
		
		//更新分类统计
		$cateid = intval($_POST['cateid']);
		if($cateid > 0){
			$count_group = $new['group']->findCount('group',array(
				'cateid'=>$cateid,
			));
			
			$new['group']->update('group_cates',array(
				'cateid'=>$cateid,
			),array(
				'count_group'=>$count_group,
			));
	
		}
		
		tsNotice('分类修改成功！');
		
		break;

}