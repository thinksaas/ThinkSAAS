<?php
defined('IN_TS') or die('Access Denied.');

switch ($ts){

    case "list":

        $userid = intval($_GET['userid']);
        $topicid = intval($_GET['topicid']);

        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $url = SITE_URL.'index.php?app=group&ac=admin&mg=comment&ts=list&page=';
        $lstart = $page*10-10;

        $where = null;

        if($userid){
            $where = array(
                'userid'=>$userid,
            );
        }

        if($topicid){
            $where = array(
                'topicid'=>$topicid,
            );
        }

        $arrComment = $new['group']->findAll('group_topic_comment',$where,'addtime desc',null,$lstart.',10');

        $commentNum = $new['group']->findCount('group_topic_comment',$where);

        $pageUrl = pagination($commentNum, 10, $page, $url);

        include template("admin/comment_list");

        break;


    case "delete":

        $commentid = intval($_GET['commentid']);

        $strComment = $new['group']->find('group_topic_comment',array(
            'commentid'=>$commentid,
        ));

        if($strComment['referid']==0){
            $new['group']->delete('group_topic_comment',array(
                'referid'=>$commentid,
            ));
        }

        $new['group']->delete('group_topic_comment',array(
            'commentid'=>$commentid,
        ));

        #统计评论数
        $count_comment = $new['group']->findCount('group_topic_comment',array(
            'topicid'=>$strComment['topicid'],
        ));

        //更新帖子最后回应时间和评论数
        $new['group']->update('group_topic',array(
            'topicid'=>$strComment['topicid'],
        ),array(
            'count_comment'=>$count_comment,
        ));


        #处理积分
        aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'],$strComment['userid'],$TS_URL['mg']);

        qiMsg('删除成功');

        break;


}