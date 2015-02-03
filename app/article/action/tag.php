<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$name = urldecode ( trim ( $_GET ['id'] ) );

$tagid = aac ( 'tag' )->getTagId ( t ( $name ) );

$strTag = $new ['article']->find ( 'tag', array (
		'tagid' => $tagid 
) );

$strTag ['tagname'] = htmlspecialchars ( $strTag ['tagname'] );

$page = isset ( $_GET ['page'] ) ? intval ( $_GET ['page'] ) : 1;

$url = tsUrl ( 'article', 'tag', array (
		'id' => urlencode ( $name ),
		'page' => '' 
) );

$lstart = $page * 30 - 30;

$arrTagId = $new ['article']->findAll ( 'tag_article_index', array (
		'tagid' => $tagid 
), null, null, $lstart . ',30' );

foreach ( $arrTagId as $item ) {
	$strArticle = $new ['article']->find ( 'article', array (
			'articleid' => $item ['articleid'] 
	) );
	if ($strArticle == '') {
		$new ['article']->delete ( 'tag_article_index', array (
				'articleid' => $item ['articleid'],
				'tagid' => $item ['tagid'] 
		) );
	}
	
	if ($strArticle) {
		$arrArticle [] = $strArticle;
	}
}

aac ( 'tag' )->countObjTag ( 'article', $tagid );

$articleNum = $new ['article']->findCount ( 'tag_article_index', array (
		'tagid' => $tagid 
) );

$pageUrl = pagination ( $articleNum, 30, $page, $url );

foreach ( $arrArticle as $key => $item ) {
	$arrArticle [$key] ['title'] = htmlspecialchars ( $item ['title'] );
	$arrArticle [$key] ['content'] = cututf8 ( t ( $item ['content'] ), 0, 150 );
	$arrArticle [$key] ['user'] = aac ( 'user' )->getOneUser ( $item ['userid'] );
}

// 热门tag
$arrTag = $new ['article']->findAll ( 'tag', "`count_article`>'0'", 'count_article desc', null, 30 );

$sitekey = $strTag ['tagname'];
$title = $strTag ['tagname'];

include template ( "tag" );