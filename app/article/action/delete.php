<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$userid = aac ( 'user' )->isLogin ();

$articleid = intval ( $_GET ['articleid'] );

$strArticle = $new ['article']->find ( 'article', array (
		'articleid' => $articleid 
) );

//普通用户不允许删除内容
if($TS_SITE['isallowdelete'] && $TS_USER ['isadmin'] == 0) tsNotice('系统不允许用户删除内容，请联系管理员删除！');



if ($strArticle ['userid'] == $userid || $TS_USER ['isadmin'] == 1) {
	$new ['article']->delete ( 'article', array (
			'articleid' => $articleid 
	) );
	$new ['article']->delete ( 'article_comment', array (
			'articleid' => $articleid 
	) );
	$new ['article']->delete ( 'article_recommend', array (
			'articleid' => $articleid 
	) );
}

header ( 'Location: ' . tsUrl ( 'article' ) );