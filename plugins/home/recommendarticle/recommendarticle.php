<?php 
defined('IN_TS') or die('Access Denied.'); 

function recommendarticle(){

	$strData = fileRead('data/plugins_home_recommendarticle.php');
	if($strData==''){
		$strData = $GLOBALS['tsMySqlCache']->get('plugins_home_recommendarticle');
	}

	if($strData['isrecommend']==1){
		$where = array(
			'isrecommend'=>1,
			'isaudit'=>0
		);
	}else{
		$where = array(
			'isaudit'=>0
		);
	}

	$arrArticle = aac('article')->findAll('article',$where,'addtime desc','articleid,cateid,userid,title,gaiyao,path,photo,count_view,count_comment,addtime,uptime',10);
	foreach($arrArticle as $key=>$item){
		$arrArticle[$key]['title'] = tsTitle($item['title']);
		$arrArticle[$key]['gaiyao'] = tsTitle($item['gaiyao']);
		$arrArticle[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
		if($item['cateid']){
			$arrArticle[$key]['cate'] = aac('article')->find('article_cate',array(
				'cateid'=>$item['cateid'],
			));
		}

		#封面图
		if($item['photo']){
			$arrArticle[$key]['photo_url'] = aac('article')->getArticlePhoto($item);
		}

	}
	
	include template('recommendarticle','recommendarticle');
	
}

addAction('home_index_left','recommendarticle');