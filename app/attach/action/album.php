<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "":
	
		$albumid = intval($_GET['id']);

		if($new['attach']->isAlbum($albumid)==false){

			tsNotice('非法操作！');

		}

		$strAlbum = $new['attach']->getOneAlbum($albumid);
		
		$strAlbum['title'] = htmlspecialchars($strAlbum['title']);
		$strAlbum['content'] = htmlspecialchars($strAlbum['content']);
		
		$strAlbum['user']= aac('user')->getOneUser($strAlbum['userid']);

		$arrAttach = $new['attach']->findAll('attach',array(
			'albumid'=>$albumid,
		));


		$title = $strAlbum['title'];
		include template('album');
		
		break;
		
	//修改
	case "edit":
		$userid = aac('user')->isLogin();
		$albumid = intval($_GET['albumid']);
		$strAlbum = $new['attach']->find('attach_album',array(
			'albumid'=>$albumid,
		));
		
		if($strAlbum['userid']==$userid || $TS_USER['user']['isadmin']==1){
		
			$title = '修改资料库';
			include template('album_edit');
		}
		
		break;
		
	case "editdo":
	
		$userid = aac('user')->isLogin();
		$albumid = intval($_POST['albumid']);
		$strAlbum = $new['attach']->find('attach_album',array(
			'albumid'=>$albumid,
		));
		
		$title = t($_POST['title']);
		$content = t($_POST['content']);
		
		if($title == '' || $content==''){
			tsNotice('资料库标题和内容不能为空！');
		}
		
		if($strAlbum['userid']==$userid || $TS_USER['user']['isadmin']==1){
		
			$new['attach']->update('attach_album',array(
				'albumid'=>$strAlbum['albumid'],
			),array(
				'title'=>$title,
				'content'=>$content,
			));
			
			header('Location: '.tsUrl('attach','album',array('id'=>$strAlbum['albumid'])));
		
		}
		

	
		break;
		
	case "delete":
	
		$userid = aac('user')->isLogin();
		$albumid = intval($_GET['albumid']);
		$strAlbum = $new['attach']->find('attach_album',array(
			'albumid'=>$albumid,
		));
		
		if($strAlbum['userid']==$userid || $TS_USER['user']['isadmin']==1){
		
			$arrAttach = $new['attach']->findAll('attach',array(
				'albumid'=>$albumid,
			));
			
			if($arrAttach){
				foreach($arrAttach as $key=>$item){
					unlink('uploadfile/attach/'.$item['attachurl']);
				}
			}
			
			$new['attach']->delete('attach',array(
				'albumid'=>$albumid,
			));
			$new['attach']->delete('attach_album',array(
				'albumid'=>$albumid,
			));
		
		}
		
		header('Location: '.tsUrl('attach'));
	
		break;
	
}