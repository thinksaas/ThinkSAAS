<?php
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

$strUser = $new['user']->getOneUser($userid);

if($userid != $strUser['userid']) header("Location: ".SITE_URL."index.php");


switch($ts){
	case "base":
		$title = '基本设置';
		include template("set_base");
		break;
		
	case "flash":
		
		$strUser = $new['user']->find('user_info',array(
			'userid'=>$userid,
		));
		
		$title = 'Flash上传头像';
		include template('set_flash');
	
		break;
		
	case "cut":
		
		$strUser = $new['user']->find('user_info',array(
			'userid'=>$userid,
		));
		
		$title = '裁切头像';
		include template('set_cut');
	
		break;
		
	case "cutdo":
	
		$strUser = $new['user']->find('user_info',array(
			'userid'=>$userid,
		));
		
		require_once 'thinksaas/tsImage.php';
		$resizeimage = new tsImage("uploadfile/user/".$strUser['face'], 190, 190, 2,"uploadfile/user/".$strUser['face']);
		
		tsDimg($strUser['face'],'user','120','120',$strUser['path']);
		tsDimg($strUser['face'],'user','48','48',$strUser['path']);
		tsDimg($strUser['face'],'user','32','32',$strUser['path']);
		tsDimg($strUser['face'],'user','24','24',$strUser['path']);
		
		header('Location: '.tsUrl('user','set',array('ts'=>'face')));
	
		break;
		
	case "face":

		$title = '头像设置';
		
		$arrFace = tsScanDir('uploadfile/user/face',1);
		
		include template("set_face");

		break;
	//执行上传头像
	case "facedo":
	
		if($_FILES['picfile']){
			
			//上传
			$arrUpload = tsUpload($_FILES['picfile'],$userid,'user',array('jpg','gif','png'));
			
			if($arrUpload){

				$new['user']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'path'=>$arrUpload['path'],
					'face'=>$arrUpload['url'],
				));
				
				$filesize=abs(filesize('uploadfile/user/'.$arrUpload['url']));
				if($filesize<=0){
					$new['user']->update('user_info',array(
						'userid'=>$userid,
					),array(
						'path'=>'',
						'face'=>'',
					));
					
					tsNotice('上传头像失败，你可以使用系统默认头像！');
					
				}else{ 
				
					//更新缓存头像
					$_SESSION['tsuser']['face'] = $arrUpload['url'];
					$_SESSION['tsuser']['path'] = $arrUpload['path'];
					
					tsDimg($arrUpload['url'],'user','120','120',$arrUpload['path']);
					tsDimg($arrUpload['url'],'user','48','48',$arrUpload['path']);
					tsDimg($arrUpload['url'],'user','32','32',$arrUpload['path']);
					tsDimg($arrUpload['url'],'user','24','24',$arrUpload['path']);
				
					header('Location: '.tsUrl('user','set',array('ts'=>'face')));
				}
				
			}else{
				tsNotice('头像修改失败');
			}
			
		}
		
		break;
	
	//设置密码
	case "pwd":
	
		$title = '密码修改';
		include template("set_pwd");

		break;
		
	//修改登录Email
	case "email":
		$title = '修改登录Email';
		include template('set_email');
		break;
		
	//设置常居地 
	case "city":
		
		//省份
		if($strUser['province']){
		
			$strProvince = $new['user']->find('area_province',array(
			
				'provinceid'=>$strUser['province'],
			
			));
		
		}
		
		//城市 
		if($strUser['city']){
		
			$strCity = $new['user']->find('area_city',array(
			
				'cityid'=>$strUser['city'],
			
			));
		
		}
		
		//区域 
		if($strUser['area']){
		
			$strArea = $new['user']->find('area',array(
			
				'areaid'=>$strUser['area'],
			
			));
		
		}
		
		
	
		//调出省份数据
		$province = $new['user']->findAll('area_province');
		
		$title = '常居地修改';
		include template("set_city");
		break;
	
	//个人标签
	case "tag":
	
		$arrTag = aac('tag')->getObjTagByObjid('user','userid',$userid);
	
		$title = '个人标签修改';
		include template("set_tag");
		break;

}