<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/15
 * Time: 23:28
 */
defined('IN_TS') or die('Access Denied.');

switch ($ts){

    case "pay":

        $userid = tsIntval($TS_USER['userid']);

        if($userid==0){
            getJson('请登录后再支付！',1,0);
        }

        $articleid = tsIntval($_POST['articleid']);

        if($articleid==0){
            getJson('文章不存在！',1,0);
        }

        $strArticle = $new['article']->find('article',array(
            'articleid'=>$articleid,
        ),'articleid,userid,score');

        if($strArticle==''){
            getJson('文章不存在！',1,0);
        }

        if($strArticle['userid']==$userid){
            getJson('自己无需支付阅读自己的文章！',1,0);
        }

        $isArticleUser = $new['article']->findCount('article_user',array(
            'articleid'=>$articleid,
            'userid'=>$userid,
        ));

        if($isArticleUser>0){
            getJson('你已经支付过，无需再次支付！',1,0);
        }

        $strUserScore = $new['article']->find('user_info',array(
            'userid'=>$userid,
        ),'userid,count_score');

        if($strUserScore['count_score']<$strArticle['score']){
            getJson('积分不足！',1,0);
        }

        aac('user')->addScore($strArticle['userid'],'文章收入'.$strArticle['articleid'],$strArticle['score'],1);
        aac('user')->delScore($userid,'查看文章'.$strArticle['articleid'],$strArticle['score']);

        $new['article']->create('article_user',array(
            'articleid'=>$articleid,
            'userid'=>$userid,
            'addtime'=>time(),
        ));


        getJson('积分支付成功！',1,2,tsUrl('article','show',array('id'=>$articleid)));

        break;

}