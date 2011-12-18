<?php

switch($ts){

	case "":
		
		$appleid = intval($_GET['appleid']);

		$userid = intval($TS_USER['user']['userid']);

		if($userid == 0){
			header("Location: ".SITE_URL);
			exit;
		}

		$strApple = $db->once_fetch_assoc("select * from ".dbprefix."apple where `appleid`='$appleid'");

		if($strApple['userid']==$userid || $TS_USER['user']['isadmin']==1){

			$index = $db->fetch_all_assoc("select * from ".dbprefix."apple_index where `appleid`='$appleid'");
			foreach($index as $key=>$item){
				$strApple['model'][] = array(
					'virtueid'	=> $item['virtueid'],
					'virtuename'	=> $new['apple']->getVirtueName($item['virtueid']),
					'parameter'	=> $item['parameter'],
				);
			}
			
			$title = '编辑';
			
			include template('edit');

		}else{

			header("Location: ".SITE_URL);
			exit;

		}
		
		break;
	
	//执行
	case "do":
	
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0){
			header("Location: ".SITE_URL);
			exit;
		}
	
		$appleid = $_POST['appleid'];
		$title = $_POST['title'];
		$content = $_POST['content'];
		
		//更新内容 
		$db->query("update ".dbprefix."apple set `title`='$title',`content`='$content' where `appleid`='$appleid'");
		
		//处理图片
		if (!empty($_FILES)) {
			
			$uptypes = array('jpg','png','gif');
			
			//1000个文件一个目录 
			$menu2=intval($appleid/1000);
			$menu1=intval($menu2/1000);
			$path = $menu1.'/'.$menu2;
			
			$dest_dir='uploadfile/apple/'.$path;
			
			createFolders($dest_dir);
			
			$photoname = $_FILES["photo"]['name'];
			
			$arrType = explode('.',$photoname);
			$phototype = $arrType[1];
			
			if (in_array($phototype,$uptypes)) {
				//上传图片
				$fileInfo=pathinfo($photoname);
				$extension=$fileInfo['extension'];
				
				$newphotoname = $appleid.'.'.$extension;
				
				$dest=$dest_dir.'/'.$newphotoname;
				move_uploaded_file($_FILES['photo']['tmp_name'], mb_convert_encoding($dest,"gb2312","UTF-8"));
				chmod($dest, 0777); 
				
				$photo = $path.'/'.$newphotoname;
				
				$db->query("update ".dbprefix."apple set `path`='$path',`photo`='$photo' where `appleid`='$appleid'");
				
			}
		}
		
		//处理模型参数
		$modelid = $_POST['modelid'];
		$virtueid = $_POST['virtueid'];
		$parameter = $_POST['parameter'];
		
		foreach($virtueid as $key=>$item){
			
			$db->query("update ".dbprefix."apple_index set `parameter`='".trim($parameter[$key])."' where `virtueid`='".$item."' and `appleid`='$appleid'");
			
		}
		
		qiMsg("修改成功");
		
		break;

} 