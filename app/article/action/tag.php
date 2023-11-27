<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

if($TS_SITE['site_urltype']==1){
	$name = urldecode(tsUrlCheck(urlencode($_GET['id'])));
}else{
	$name = urldecode(tsUrlCheck($_GET['id']));
}

//$name=mb_convert_encoding($name,'UTF-8', 'GB2312'); //针对IIS环境可能出现的问题请取消此行注释

$strTag = aac('tag')->getTagByName($name);

$strTag ['tagname'] = htmlspecialchars ( $strTag ['tagname'] );
 
$page = tsIntval($_GET['page'],1);

$url = tsUrl ( 'article', 'tag', array (
		'id' => urlencode ( $name ),
		'page' => '' 
) );

$lstart = $page * 30 - 30;

$tagid = $strTag['tagid'];

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
	
	if ($strArticle && $strArticle['isaudit']==0) {
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
	$arrArticle [$key] ['user'] = aac ( 'user' )->getSimpleUser ( $item ['userid'] );
    $arrArticle [$key] ['cate'] = $new ['article']->find ( 'article_cate', array (
        'cateid' => $item ['cateid']
    ) );
}

// 热门tag
$arrTag = $new ['article']->findAll ( 'tag', "`count_article`>'0' and `isaudit`=0", 'count_article desc', null, 30 );

$sitekey = $strTag ['tagname'];
$title = $strTag ['tagname'];

include template ( "tag" );