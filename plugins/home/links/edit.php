<?php
defined('IN_TS') or die('Access Denied.');
//插件编辑
switch($ts){
	case "set":
		$arrLink = fileRead('data.php','plugins','home','links');
		
		include 'edit_set.html';
		break;
		
	case "do":
		$arrLinkName = $_POST['linkname'];
		$arrLinkUrl = $_POST['linkurl'];
		
		foreach($arrLinkName as $key=>$item){
			$linkname = trim($item);
			$linkurl = trim($arrLinkUrl[$key]);
			if($linkname && $linkurl){
				$arrLink[] = array(
					'linkname'	=> $linkname,
					'linkurl'	=> $linkurl,
				);
			}
			
		}
		
		fileWrite('data.php','plugins/home/links',$arrLink);
		
		qiMsg("修改成功！");
		break;
}