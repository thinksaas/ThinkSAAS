<?php
defined('IN_TS') or die('Access Denied.');

switch ($ts){

    case "pay":

        $userid = tsIntval($TS_USER['userid']);

        if($userid==0){
            getJson('请登录后再支付！',1,0);
        }

        $topicid = tsIntval($_POST['topicid']);

        if($topicid==0){
            getJson('帖子不存在！',1,0);
        }

        $strTopic = $new['topic']->find('topic',array(
            'topicid'=>$topicid,
        ),'topicid,userid,score');

        if($strTopic==''){
            getJson('帖子不存在！',1,0);
        }

        if($strTopic['userid']==$userid){
            getJson('自己无需支付阅读自己的帖子！',1,0);
        }

        $isTopicUser = $new['topic']->findCount('topic_user',array(
            'topicid'=>$topicid,
            'userid'=>$userid,
        ));

        if($isTopicUser>0){
            getJson('你已经支付过，无需再次支付！',1,0);
        }

        $strUserScore = $new['topic']->find('user_info',array(
            'userid'=>$userid,
        ),'userid,count_score');

        if($strUserScore['count_score']<$strTopic['score']){
            getJson('积分不足！',1,0);
        }

        aac('user')->addScore($strTopic['userid'],'帖子收入'.$strTopic['topicid'],$strTopic['score'],1);
        aac('user')->delScore($userid,'查看帖子'.$strTopic['topicid'],$strTopic['score']);

        $new['topic']->create('topic_user',array(
            'topicid'=>$topicid,
            'userid'=>$userid,
            'addtime'=>time(),
        ));


        getJson('积分支付成功！',1,2,tsUrl('topic','show',array('id'=>$topicid)));

        break;

}