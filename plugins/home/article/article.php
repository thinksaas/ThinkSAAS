<?php 
defined('IN_TS') or die('Access Denied.'); 

function article(){
	
	$arrArticle = aac('article')->findAll('article',array(
		'isaudit'=>0
	),'addtime desc','articleid,cateid,userid,title,gaiyao,path,photo,count_view,count_comment,addtime',10);
	foreach($arrArticle as $key=>$item){
		$arrArticle[$key]['title'] = tsTitle($item['title']);
		$arrArticle[$key]['content'] = tsDecode($item['content']);
		$arrArticle[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
		if($item['cateid']){
			$arrArticle[$key]['cate'] = aac('article')->find('article_cate',array(
				'cateid'=>$item['cateid'],
			));
		}
	}
	
	include template('article','article');
	
}

addAction('home_index_right','article');