<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	//角色列表
	case "list":
		
		$arrRole = $new['user']->findAll('user_role');
		
		include template('admin/role_list');
		break;
	
	//角色修改
	case "do":
		
		$arrRoleName = $_POST['rolename'];
		$arrScoreStart = $_POST['score_start'];
		$arrScoreEnd = $_POST['score_end'];
		
		//先清空
		$db->query("TRUNCATE TABLE `".dbprefix."user_role`");
		
		//后添加
		foreach($arrRoleName as $key=>$item){
			$rolename = trim($item);
			$score_start = trim($arrScoreStart[$key]);
			$score_end = trim($arrScoreEnd[$key]);
			
			if($rolename){
				$new['user']->create('user_role',array(
					'rolename'=>$rolename,
					'score_start'=>$score_start,
					'score_end'=>$score_end,
				));
			}
		}
		
		//再生成缓存文件
		$arrRole = $new['user']->findAll('user_role',null,null,'rolename,score_start,score_end');
		
		fileWrite('user_role.php','data',$arrRole);
		$tsMySqlCache->set('user_role',$arrRole);
		
		qiMsg("修改成功！");
		
		break;
	
}