<?php 
defined('IN_TS') or die('Access Denied.');



switch($ts){

	//列表 
	case "list":
	
		$page = isset($_GET['page']) ? $_GET['page'] : '1';
		$url = SITE_URL.'index.php?app=attach&ac=admin&mg=album&ts=list&page=';
		$lstart = $page*10-10;
		
		$albumNum = $new['attach']->findCount('attach_album');
		
		$pageUrl = pagination($albumNum, 10, $page, $url);
		
		$arrAlbum = $new['attach']->findAll('attach_album',null,'addtime desc',null,$lstart.',10');
	
		include template('admin/album_list');
		break;
	
	//删除
	case "delete":
	
		$albumid = intval($_GET['albumid']);
		
		$arrAttach = $new['attach']->find('attach',array(
			'albumid'=>$albumid,
		));
		
		foreach($arrAttach as $key=>$item){
			unlink('uploadfile/attach/'.$item['attachurl']);
			$new['attach']->delete('attach',array(
				'attachid'=>$item['attachid'],
			));
		}
		
		$new['attach']->delete('attach',array('albumid'=>$albumid));
		$new['attach']->delete('attach_album',array('albumid'=>$albumid));
		
		qiMsg('删除成功');
	
		break;
		
	case "isaudit":
		
		$albumid = intval($_GET['albumid']);
		
		$strAlbum = $new['attach']->find('attach_album',array(
			'albumid'=>$albumid,
		));
		
		if($strAlbum['isaudit']==1){
			$new['attach']->update('attach_album',array(
				'albumid'=>$albumid,
			),array(
				'isaudit'=>0
			));
		}
		
		if($strAlbum['isaudit']==0){
			$new['attach']->update('attach_album',array(
				'albumid'=>$albumid,
			),array(
				'isaudit'=>1
			));
		}
		
		qiMsg('操作成功！');
		
		break;
	
}