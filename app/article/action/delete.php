<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$userid = aac ( 'user' )->isLogin ();

$articleid = tsIntval ( $_GET ['articleid'] );

$strArticle = $new ['article']->find ( 'article', array (
    'articleid' => $articleid
) );

//普通用户不允许删除内容
if($TS_SITE['isallowdelete'] && $TS_USER ['isadmin'] == 0) tsNotice('系统不允许用户删除内容，请联系管理员删除！');

if ($strArticle ['userid'] == $userid || $TS_USER ['isadmin'] == 1) {


    #用户记录
	aac('pubs')->addLogs('article','articleid',$articleid,$userid,$strArticle['title'],$strArticle['content'],2);


    #删除文章
    $new['article']->deleteArticle($strArticle);

    if($strArticle['isaudit']==0){
        #对积分进行处理
        aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'],$strArticle ['userid']);
    }

}

tsNotice('删除成功','点击返回文章首页',tsUrl ( 'article' ));