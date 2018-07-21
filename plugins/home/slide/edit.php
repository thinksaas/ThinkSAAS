<?php
defined('IN_TS') or die('Access Denied.');
//插件编辑
switch($ts){
	case "set":
	
		$arrData = $new[$app]->findAll('slide',null,'slideid asc');
		

		
		include template('edit_set','slide');
		break;
		
	case "do":
	

		$typeid = intval($_POST['typeid']);
		$title = trim($_POST['title']);
		$info = trim($_POST['info']);
		$url = trim($_POST['url']);
		
		$slideid = $new[$app]->create('slide',array(
            'typeid'=>$typeid,
			'title'=>$title,
			'info'=>$info,
			'url'=>$url,
			'addtime'=>time(),
		));
		
		
		//上传
		$arrUpload = tsUpload($_FILES['photo'],$slideid,'slide',array('jpg','gif','png','jpeg'));
		
		if($arrUpload){

			$new[$app]->update('slide',array(
				'slideid'=>$slideid,
			),array(
				'path'=>$arrUpload['path'],
				'photo'=>$arrUpload['url'],
			));
		}
		
		header('Location: '.SITE_URL.'index.php?app=home&ac=plugin&plugin=slide&in=edit&ts=set');
		break;
		
	case "edit":
		
		$slideid = intval($_GET['slideid']);
		
		$strSlide = $new[$app]->find('slide',array(
			'slideid'=>$slideid,
		));
		

        include template('edit_edit','slide');
		break;
		
	case "editdo":
		
		$slideid = intval($_POST['slideid']);
		$typeid = intval($_POST['typeid']);
		$title = trim($_POST['title']);
		$info = trim($_POST['info']);
		$url = trim($_POST['url']);
		
		$new[$app]->update('slide',array(
			'slideid'=>$slideid,
		),array(
			'typeid'=>$typeid,
			'title'=>$title,
			'info'=>$info,
			'url'=>$url,
		));
		
		
		//上传
		$arrUpload = tsUpload($_FILES['photo'],$slideid,'slide',array('jpg','gif','png','jpeg'));
		
		if($arrUpload){

			$new[$app]->update('slide',array(
				'slideid'=>$slideid,
			),array(
				'path'=>$arrUpload['path'],
				'photo'=>$arrUpload['url'],
			));
			
			tsDimg($arrUpload['url'],'slide','748','210',$arrUpload['path']);
			
		}
		
		header('Location: '.SITE_URL.'index.php?app=home&ac=plugin&plugin=slide&in=edit&ts=set');
		
		break;
		
	case "delete":
		
		$slideid = intval($_GET['slideid']);
		
		$strSlide = $new[$app]->find('slide',array(
			'slideid'=>$slideid,
		));
		
		unlink('uploadfile/slide/'.$strSlide['photo']);
		
		$new[$app]->delete('slide',array(
			'slideid'=>$slideid,
		));
		
		header('Location: '.SITE_URL.'index.php?app=home&ac=plugin&plugin=slide&in=edit&ts=set');
		
		break;
}