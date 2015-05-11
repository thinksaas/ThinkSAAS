<?php 
defined('IN_TS') or die('Access Denied.');



switch($ts){

	//列表 
	case "list":
	
		$page = isset($_GET['page']) ? $_GET['page'] : '1';
		$url = SITE_URL.'index.php?app=attach&ac=admin&mg=attach&ts=list&page=';
		$lstart = $page*10-10;
		
		$attachNum = $new['attach']->findCount('attach');
		
		$pageUrl = pagination($attachNum, 12, $page, $url);
		
		$arrAttach = $new['attach']->findAll('attach',null,'addtime desc',null,$lstart.',10');
	
		include template('admin/attach_list');
		break;
	
	//删除
	case "delete":
	
		$attachid = intval($_GET['attachid']);
		
		$strAttach = $new['attach']->find('attach',array(
			'attachid'=>$attachid,
		));
		
		if($strAttach){
			unlink('uploadfile/attach/'.$strAttach['attachurl']);
			$new['attach']->delete('attach',array(
				'attachid'=>$attachid,
			));
		}
		
		qiMsg('删除成功');
	
		break;
		
	case "isaudit":
		
		$attachid = intval($_GET['attachid']);
		
		$strAttach = $new['attach']->find('attach',array(
			'attachid'=>$attachid,
		));
		
		if($strAttach['isaudit']==1){
			$new['attach']->update('attach',array(
				'attachid'=>$attachid,
			),array(
				'isaudit'=>0
			));
		}
		
		if($strAttach['isaudit']==0){
			$new['attach']->update('attach',array(
				'attachid'=>$attachid,
			),array(
				'isaudit'=>1
			));
		}
		
		qiMsg('操作成功！');
		
		break;
	
}