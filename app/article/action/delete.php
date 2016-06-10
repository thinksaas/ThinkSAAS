<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$userid = aac ( 'user' )->isLogin ();

if ($_GET['token'] != $_SESSION['token']) {
    tsNotice('非法操作！');
}

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

tsNotice('删除成功','点击返回文章首页',tsUrl ( 'article' ));