<?php
defined('IN_TS') or die('Access Denied.'); 
if(intval($TS_USER['user']['isadmin']) == 1){

	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
	$url = SITE_URL.tsurl('article','audit',array('page'=>''));
	$lstart = $page*10-10;
	$arrArticle = $db->fetch_all_assoc("select * from ".dbprefix."article where `isaudit`='1' order by addtime desc limit $lstart,10");
	
	$articleNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."article where `isaudit`='1'");
	
	$auditNum = $articleNum['count(*)'];

	$pageUrl = pagination($articleNum['count(*)'], 10, $page, $url);
	
	//分类 
	$arrCate = $new['article']->getArrCate();
	
	$title = '未审核文章';
	include template('audit');

	
}else{
	
	header("Location: ".SITE_URL);
	
}