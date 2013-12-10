<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "":
	
		$userid = intval($_GET['userid']);
		$menu2 = intval($userid/1000);

		$menu1=intval($menu2/1000);

		$path = $menu1.'/'.$menu2;

		$dest_dir='uploadfile/user/'.$path;

		createFolders($dest_dir);

		$file_src = "src.png"; 
		$filename162 = $userid.".png";   

		$src=base64_decode($_POST['pic']);
		$pic1=base64_decode($_POST['pic1']);   

		if($src) {
			file_put_contents($file_src,$src);
		}

		file_put_contents($dest_dir.'/'.$filename162,$pic1);

		//更新数据库
		$new['user']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'path'=>$path,
			'face'=>$path.'/'.$filename162,
		));
		
		//清除缓存文件
		tsDimg($path.'/'.$filename162,'user','120','120',$path);
		tsDimg($path.'/'.$filename162,'user','48','48',$path);
		tsDimg($path.'/'.$filename162,'user','32','32',$path);
		tsDimg($path.'/'.$filename162,'user','24','24',$path);

		$rs['status'] = 1;

		print json_encode($rs);
	
		break;
		
	case "face":
		
		$userid = intval($_GET['userid']);
		$menu2 = intval($userid/1000);
		$menu1=intval($menu2/1000);
		$path = $menu1.'/'.$menu2;
		
		echo 'uploadfile/user/'.$path.'/'.$userid.'.png';
		
		break;

}