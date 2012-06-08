<?php
defined('IN_TS') or die('Access Denied.');
//插件编辑
switch($ts){
	case "set":
		
		
		include 'edit_set.html';
		break;
	
	
	case "do":
		
		//生成sitemap
		$arrUrl = SITE_URL."\n";

		$arrGroup = $new['pubs']->findAll('group');
		foreach($arrGroup as $key=>$item){
			$arrUrl .= SITE_URL.tsUrl('group','show',array('id'=>$item['groupid']))."\n";
		}

		fileWrite('sitemap.txt','plugins/pubs/sitemap',$arrUrl,0);
		
		qiMsg("修改成功！");
		break;
}