<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$articleid = tsIntval($_GET ['id']);

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


// 上一篇
$strUp = $new['article']->find('article', "`articleid`< '$articleid' and `isaudit`='0'", 'articleid,title','articleid desc');
if($strUp) $strUp['title'] = tsTitle($strUp['title']);
// 下一篇
$strNext = $new['article']->find('article', "`articleid`> '$articleid' and `isaudit`='0'", 'articleid,title','articleid asc');
if($strNext) $strNext['title'] = tsTitle($strNext['title']);



// 获取评论
$page = tsIntval($_GET['page'],1);
$url = tsUrl ('article','show', array ('id' => $articleid,'page'=>''));
$lstart = $page * 15 - 15;
$arrComment = aac('pubs')->getCommentList('article','articleid',$strArticle['articleid'],$page,$lstart,$strArticle['userid']);
$commentNum = aac('pubs')->getCommentNum('article','articleid',$strArticle['articleid']);
$pageUrl = pagination ( $commentNum, 15, $page, $url );

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



//判断用户可阅读文章：0可读1不可读
$isread = 0;
if($strArticle['score']>0) $isread = 1;
if($TS_USER['userid'] && $strArticle['userid']==$TS_USER['userid']) $isread=0;
if($TS_USER['userid'] && $strArticle['userid']!=$TS_USER['userid'] && $strArticle['score']>0){
    $isArticleUser = $new['article']->findCount('article_user',array(
        'articleid'=>$articleid,
        'userid'=>$TS_USER['userid'],
    ));
    if($isArticleUser>0) $isread=0;
}
if($TS_USER['isadmin']==1) $isread=0;




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
$count_view = $strArticle ['count_view'] + 1;
$new ['article']->update ('article', array(
	'articleid' => $strArticle ['articleid'] 
), array (
	'count_view' => $count_view, 
));

#更新ptable
aac('pubs')->upPtableView('article','articleid',$articleid,$count_view);