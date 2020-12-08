<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "list":
		
		$page = isset($_GET['page']) ? tsIntval($_GET['page']) : 1;
		
		$lstart = $page*10-10;
		
		$url = SITE_URL.'index.php?app=photo&ac=admin&mg=album&ts=list&page=';
		
		$arrAlbum = $new['photo']->findAll('photo_album',null,'albumid desc',null,$lstart.',10');
		
		
		$albumNum = $new['photo']->findCount('photo_album');
		
		
		$pageUrl = pagination($albumNum, 10, $page, $url);
		
		include template("admin/album_list");
		break;
	
	//图片 
	case "photo":
		$albumid = tsIntval($_GET['albumid']);
		
		$page = isset($_GET['page']) ? tsIntval($_GET['page']) : 1;
		
		$lstart = $page*10-10;
		
		$url = SITE_URL.'index.php?app=photo&ac=admin&mg=album&ts=photo&albumid='.$albumid.'&page=';
		
		$arrPhoto = $db->fetch_all_assoc("select * from ".dbprefix."photo where albumid='$albumid' limit $lstart,10");
		
		$photo_num = $db->once_fetch_assoc("select count(photoid) from ".dbprefix."photo where albumid='$albumid'");
		
		$pageUrl = pagination($photo_num['count(photoid)'], 10, $page, $url);
		
		include template("admin/album_photo");
		
		break;
		
	//删除相册
	case "del_album":
		$albumid = tsIntval($_GET['albumid']);
		
		$new['photo']->deletePhotoAlbum($albumid);
		
		qiMsg("相册删除成功！");
		
		break;
		
	//删除照片
	case "del_photo":
		$photoid = tsIntval($_GET['photoid']);

		$strPhoto = $new['photo']->find('photo',array(
			'photoid'=>$photoid,
		));

		$albumid = $strPhoto['albumid'];
		
		$new['photo']->deletePhoto($strPhoto);
		
		$count_photo = $db->once_num_rows("select * from ".dbprefix."photo where albumid='$albumid'");
		
		$db->query("update ".dbprefix."photo_album set `count_photo`='$count_photo' where albumid='$albumid'");
		
		qiMsg("图片删除成功!");
		
		break;
		
	//设为封面
	case "face":
		$photoid = tsIntval($_GET['photoid']);
		$strPhoto = $db->once_fetch_assoc("select * from ".dbprefix."photo where photoid='$photoid'");
		
		$albumid = $strPhoto['albumid'];
		$albumface = $strPhoto['photourl'];
		
		$db->query("update ".dbprefix."photo_album set `albumface`='$albumface' where albumid='$albumid'");
		
		qiMsg("封面设置成功！");
		
		break;
		
	//统计 
	case "count":
		
		$arrAlbum = $db->fetch_all_assoc("select albumid from ".dbprefix."photo_album");
		
		foreach($arrAlbum as $item){
			$albumid = $item['albumid'];
			$count_photo = $db->once_num_rows("select photoid from ".dbprefix."photo where albumid='$albumid'");
			$db->query("update ".dbprefix."photo_album set `count_photo`='$count_photo' where albumid='$albumid'");
		}
		
		qiMsg("统计完成！");
		
		break;
		
	//推荐相册 
	case "isrecommend":
	
		$albumid = tsIntval($_GET['albumid']);
		
		$strAlbum = $db->once_fetch_assoc("select isrecommend from ".dbprefix."photo_album where `albumid`='$albumid'");
		
		if($strAlbum['isrecommend']==0){
			$db->query("update ".dbprefix."photo_album set `isrecommend`='1' where `albumid`='$albumid'");
		}else{
			$db->query("update ".dbprefix."photo_album set `isrecommend`='0' where `albumid`='$albumid'");
		}
	
		qiMsg("操作成功！");
	
		break;

    //是否审核
    case "isaudit":

        $albumid = tsIntval($_GET['albumid']);

        $strAlbum = $new['photo']->find('photo_album',array(
            'albumid'=>$albumid,
        ));

        if($strAlbum['isaudit']==0){

            $new['photo']->update('photo_album',array(
                'albumid'=>$albumid,
            ),array(
                'isaudit'=>1,
            ));

        }else{
            $new['photo']->update('photo_album',array(
                'albumid'=>$albumid,
            ),array(
                'isaudit'=>0,
            ));
        }

        qiMsg("操作成功！");

        break;
		
	//删除没有图片的相册
	case "nophoto":
	
		$arrAlbum = $new['photo']->findAll('photo_album',"`count_photo`=0");
		foreach($arrAlbum as $key=>$item){
		
			$isPhoto = $new['photo']->findCount('photo',array(
				'albumid'=>$item['albumid'],
			));
			
			if($isPhoto == 0){
			
				$new['photo']->delete('photo_album',array(
				
					'albumid'=>$item['albumid'],
				
				));
			
			}else{
			
				$count_photo = $new['photo']->findCount('photo',array(
				
					'albumid'=>$item['albumid'],
				
				));
				
				$new['photo']->update('photo_album',array(
					'albumid'=>$item['albumid'],
				),array(
				
					'count_photo'=>$count_photo,
				
				));
			
			}
		
		}
		
		qiMsg('操作成功！');
	
		break;
		
	case "isaudit":
		
		$albumid = tsIntval($_GET['albumid']);
		
		$strAlbum = $new['attach']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		
		if($strAlbum['isaudit']==1){
			$new['attach']->update('photo_album',array(
				'albumid'=>$albumid,
			),array(
				'isaudit'=>0
			));
		}
		
		if($strAlbum['isaudit']==0){
			$new['attach']->update('photo_album',array(
				'albumid'=>$albumid,
			),array(
				'isaudit'=>1
			));
		}
		
		qiMsg('操作成功！');
		
		break;
	
}