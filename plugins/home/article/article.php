<?php 
defined('IN_TS') or die('Access Denied.'); 

function article(){
	
	$arrArticle = aac('article')->findAll('article',array('isaudit'=>0),'addtime desc',null,10);
	foreach($arrArticle as $key=>$item){
		$arrArticle[$key]['title'] = tsTitle($item['title']);
		$arrArticle[$key]['content'] = tsDecode($item['content']);
	}
	
	include template('article','article');
	
}

addAction('home_index_right','article');