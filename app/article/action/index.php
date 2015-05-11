<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

// 列表
$page = isset ( $_GET ['page'] ) ? intval ( $_GET ['page'] ) : 1;
$url = tsUrl ( 'article', 'index', array (
		'page' => '' 
) );
$lstart = $page * 10 - 10;

$arrArticles = $new ['article']->findAll ( 'article', array (
		'isaudit' => '0' 
), 'addtime desc', null, $lstart . ',10' );

$articleNum = $new ['article']->findCount ( 'article', array (
		'isaudit' => '0' 
) );

$pageUrl = pagination ( $articleNum, 10, $page, $url );

foreach ( $arrArticles as $key => $item ) {
	$arrArticle [] = $item;
	$arrArticle [$key]['title'] = tsTitle($item['title']);
	$arrArticle [$key] ['content'] = cututf8 ( t(tsDecode ( $item ['content'] )), 0, 100 );
	$arrArticle [$key] ['user'] = aac ( 'user' )->getOneUser ( $item ['userid'] );
	$arrArticle [$key] ['cate'] = $new ['article']->find ( 'article_cate', array (
			'cateid' => $item ['cateid'] 
	) );
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