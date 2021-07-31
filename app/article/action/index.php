<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$cateid = 0;

// 列表
$page = tsIntval($_GET['page'],1);
$url = tsUrl ( 'article', 'index', array ('page' => '') );
$lstart = $page * 10 - 10;

$arrArticle = $new ['article']->findAll ( 'article', array (
		'isaudit' => '0' 
), 'istop desc,addtime desc', 'articleid,userid,cateid,title,gaiyao,score,path,photo,count_comment,count_love,count_view,istop,addtime,uptime', $lstart . ',10' );

$articleNum = $new ['article']->findCount ( 'article', array (
    'isaudit' => '0'
) );

$pageUrl = pagination ( $articleNum, 10, $page, $url );

foreach ( $arrArticle as $key => $item ) {
	$arrArticle [$key]['title'] = tsTitle($item['title']);
	$arrArticle [$key]['gaiyao'] = tsTitle($item['gaiyao']);
	$arrArticle [$key] ['user'] = aac ( 'user' )->getSimpleUser( $item ['userid'] );
	$arrArticle [$key] ['cate'] = $new ['article']->find( 'article_cate', array (
        'cateid' => $item ['cateid']
	) );

	#封面图
	if($item['photo']){
		$arrArticle[$key]['photo_url'] = $new['article']->getArticlePhoto($item);
	}
	

}

// 推荐阅读
$arrRecommend = $new ['article']->getRecommendArticle ();

// 一周热门
$arrHot7 = $new ['article']->getHotArticle ( 7 );
// 一月热门
$arrHot30 = $new ['article']->getHotArticle ( 30 );


$sitekey = $TS_APP['appkey'];
$sitedesc = $TS_APP['appdesc'];
$title = '文章';

include template ( 'index' );