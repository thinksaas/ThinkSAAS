<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$articleid = intval ( $_GET ['id'] );

$strArticle = $new ['article']->find ( 'article', array (
		'articleid' => $articleid 
) );

if ($articleid == 0 || $strArticle == '') {
	header ( "HTTP/1.1 404 Not Found" );
	header ( "Status: 404 Not Found" );
	$title = '404';
	include pubTemplate ( "404" );
	exit ();
}

// 是否审核
if ($strArticle ['isaudit'] == 1 && $TS_USER['isadmin']==0 && $TS_USER['userid']!=$strArticle['userid']) {
	tsNotice ( '内容审核中...' );
}

$cateid = $strArticle['cateid'];

$strArticle['title'] = tsTitle($strArticle['title']);

$tpUrl = tpPage($strArticle['content'],'article','show',array('id'=>$strArticle['articleid']));

$strArticle['content'] = tsDecode($strArticle['content'],$tp);

$strArticle ['tags'] = aac ( 'tag' )->getObjTagByObjid ( 'article', 'articleid', $articleid );
$strArticle ['user'] = aac ( 'user' )->getSimpleUser ( $strArticle ['userid'] );
$strArticle ['cate'] = $new ['article']->find ( 'article_cate', array (
		'cateid' => $strArticle ['cateid'] 
) );


####文章关联视频APP开始####
if($TS_APP['isarticlevideo']){
    $arrVideo = $new['article']->getArticleVideo($strArticle['articleid']);
}
####文章关联视频APP开始####



// 上一篇
$strUp = $new['article']->find('article', "`articleid`< '$articleid' and `isaudit`='0'", 'articleid,title','articleid desc');
if($strUp) $strUp['title'] = tsTitle($strUp['title']);
// 下一篇
$strNext = $new['article']->find('article', "`articleid`> '$articleid' and `isaudit`='0'", 'articleid,title','articleid asc');
if($strNext) $strNext['title'] = tsTitle($strNext['title']);



// 获取评论
$page = isset ( $_GET ['page'] ) ? intval ( $_GET ['page'] ) : 1;
$url = tsUrl ( 'article', 'show', array (
		'id' => $articleid,
		'page' => '' 
) );
$lstart = $page * 10 - 10;

$arrComments = $new ['article']->findAll ( 'article_comment', array (
		'articleid' => $articleid 
), 'addtime desc', null, $lstart . ',10' );

foreach ( $arrComments as $key => $item ) {
	$arrComment [] = $item;
	$arrComment[$key]['content'] = tsDecode($item['content']);
	$arrComment [$key] ['user'] = aac ( 'user' )->getSimpleUser ( $item ['userid'] );
}

$commentNum = $new ['article']->findCount ( 'article_comment', array (
		'articleid' => $articleid 
) );

$pageUrl = pagination ( $commentNum, 10, $page, $url );

// 标签
$strArticle ['tags'] = aac ( 'tag' )->getObjTagByObjid ( 'article', 'articleid', $strArticle ['articleid'] );

//最新文章
$arrArticle = $new ['article']->findAll ( 'article', array(
    'isaudit'=>0,
), 'addtime desc', 'articleid,title', 10 );

// 推荐阅读
$arrRecommend = $new ['article']->getRecommendArticle ();

// 一周热门
$arrHot7 = $new ['article']->getHotArticle ( 7);
// 一月热门
$arrHot30 = $new ['article']->getHotArticle ( 30);

//把标签作为关键词
if($strArticle['tags']){
	foreach($strArticle['tags'] as $key=>$item){
		$arrTag[] = $item['tagname'];
	}
	$sitekey = arr2str($arrTag);
}else{
	$sitekey = $strArticle['title'];
}

$sitedesc = cututf8(t($strArticle['content']),0,100);

$title = $strArticle ['title'];

include template ( 'show' );

// 统计查看次数
$new ['article']->update ( 'article', array (
		'articleid' => $strArticle ['articleid'] 
), array (
		'count_view' => $strArticle ['count_view'] + 1 
) );