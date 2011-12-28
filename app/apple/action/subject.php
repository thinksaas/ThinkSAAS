<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	//苹果机
	case "":
		$appleid = $_GET['appleid'];

		$strApple = $db->once_fetch_assoc("select * from ".dbprefix."apple where `appleid`='$appleid'");

		$index = $db->fetch_all_assoc("select * from ".dbprefix."apple_index where `appleid`='$appleid'");
		foreach($index as $key=>$item){
			$strApple['model'][] = array(
				'virtuename'	=> $new['apple']->getVirtueName($item['virtueid']),
				'parameter'	=> $item['parameter'],
			);
		}

		//print_r($strApple);

		//计算打分人数和平均分
		$userNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."apple_score where `appleid`='$appleid'");
		$userNumCount = $userNum['count(*)'];

		$score = $db->once_fetch_assoc("select sum(score) from ".dbprefix."apple_score where `appleid`='$appleid'");

		//总分
		$allScore = $score['sum(score)'];

		//平均分
		$average = round($allScore/$userNumCount,2);

		//计算当前用户的评分 
		if(intval($TS_USER['user']['userid'])>0){
			$userid = intval($TS_USER['user']['userid']);
			$userScore = $db->once_fetch_assoc("select count(*) from ".dbprefix."apple_score where `appleid`='$appleid' and `userid`='$userid'");
			$userScoreCount = $userScore['count(*)'];
			if($userScoreCount > 0){
				$strScore = $db->once_fetch_assoc("select * from ".dbprefix."apple_score where `appleid`='$appleid' and `userid`='$userid'");
			}
		}

		//获取点评列表
		$arrReviews= $db->fetch_all_assoc("select * from ".dbprefix."apple_review where `appleid`='$appleid' limit 5");
		foreach($arrReviews as $key=>$item){
			$arrReview[] = $item;
			$arrReview[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
			$arrReview[$key]['content'] = getsubstrutf8($item['content'],0,100);
		}
		//计算点评数 
		$reviewNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."apple_review where `appleid`='$appleid'");

		$reviewNumCount = $reviewNum['count(*)'];

		$title = $strApple['title'];

		include template('subject');
		break;
		
	//列表
	case "list":
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.tsurl('apple','list',array('page'=>''));
		$lstart = $page*20-20;

		$arrApples = $db->fetch_all_assoc("select * from ".dbprefix."apple order by addtime desc limit $lstart,20");

		$appleNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."apple");
		$pageUrl = pagination($appleNum['count(*)'], 20, $page, $url);

		foreach($arrApples as $key=>$item){
			$arrApple[] = $item;
			$index = $db->fetch_all_assoc("select * from ".dbprefix."apple_index where `appleid`='".$item['appleid']."'");
			foreach($index as $mkey=>$mitem){
				$arrApple[$key]['model'][] = array(
					'virtuename'	=> $new['apple']->getVirtueName($mitem['virtueid']),
					'parameter'	=> $mitem['parameter'],
				);
			}
		}

		$title = '列表';

		include template('subject_list');
		break;
		
	//添加苹果机	
	case "add":
		
		if(intval($TS_USER['user']['isadmin'])==0){
			header("Location: ".SITE_URL);
			exit;
		}
		
		$userid = $TS_USER['user']['userid'];
		
		$modelid = $TS_APP['options']['modelid'];
		//获取模型属性 
		$strModel = $db->fetch_all_assoc("select * from ".dbprefix."apple_virtue where `modelid`='$modelid'");
		
		$title = '生产苹果机';
		include template('subject_add');
		break;
	
	//执行添加
	case "add_do":
		
		$userid = intval($TS_USER['user']['userid']);
		
		if($userid == 0){
			header("Location: ".SITE_URL);
			exit;
		}
		
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		
		//获取appleid
		$db->query("insert into ".dbprefix."apple (`userid`,`title`,`content`,`addtime`) values ('$userid','$title','$content','".time()."')");
		$appleid = $db->insert_id();
		
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
			$phototype = array_pop($arrType);
			
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
			$db->query("insert into ".dbprefix."apple_index (`appleid`,`modelid`,`virtueid`,`parameter`) values ('$appleid','$modelid','".$item."','".trim($parameter[$key])."')");
		}
		
		header("Location: ".SITE_URL.tsurl('apple','subject',array('appleid'=>$appleid)));
		
		break;
	
	//编辑
	case "edit":
		
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
			
			include template('subject_edit');

		}else{

			header("Location: ".SITE_URL);
			exit;

		}
		
		break;
	
	//编辑执行
	case "edit_do":
	
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
		
	//删除项目
	case "del":
		
		$appleid = intval($_GET['appleid']);
		
		if($TS_USER['user']['isadmin']!='1'){
			header("Location: ".SITE_URL);
			exit;
		}
		
		$arrReview = $db->fetch_all_assoc("select * from ".dbprefix."apple_review where `appleid`='$appleid'");
		foreach($arrReview as $item){
			$db->query("delete from ".dbprefix."apple_comment where `reviewid`='".$item['reviewid']."'");
		}
		
		$db->query("delete from ".dbprefix."apple where `appleid`='$appleid'");
		$db->query("delete from ".dbprefix."apple_index where `appleid`='$appleid'");
		$db->query("delete from ".dbprefix."apple_review where `appleid`='$appleid'");
		$db->query("delete from ".dbprefix."apple_score where `appleid`='$appleid'");
		
		header("Location: ".tsurl('apple'));
		
		break;
		
}
