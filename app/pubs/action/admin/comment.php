<?php
defined('IN_TS') or die('Access Denied.');

switch ($ts){

    case "list":

        $userid = intval($_GET['userid']);
        $ptable = isset($_GET['ptable']) ? trim($_GET['ptable']) : 'group_topic';
        $pid = intval($_GET['pid']);

        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $url = SITE_URL.'index.php?app=pubs&ac=admin&mg=comment&ts=list&&page=';
        $lstart = $page*10-10;

        $where = array(
            'ptable'=>$ptable,
        );

        if($userid){
            $where = array(
                'ptable'=>$ptable,
                'userid'=>$userid,
            );
        }

        if($pid){
            $where = array(
                'ptable'=>$ptable,
                'pid'=>$pid,
            );
        }

        $arrComment = $new['pubs']->findAll('comment',$where,'addtime desc',null,$lstart.',10');

        $commentNum = $new['pubs']->findCount('comment',$where);

        $pageUrl = pagination($commentNum, 10, $page, $url);

        include template("admin/comment_list");

        break;


    case "delete":

        $commentid = intval($_GET['commentid']);

        $strComment = $new['pubs']->find('comment',array(
            'commentid'=>$commentid,
        ));

        $ptable = $strComment['ptable'];
        $pkey = $strComment['pkey'];
        $pid = $strComment['pid'];

        $new['pubs']->delete('comment',array(
            'commentid'=>$commentid,
        ));

        $new['pubs']->delComment($ptable,$pkey,$pid,$commentid);

        #处理积分
        aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'],$strComment['userid'],$TS_URL['mg']);

        qiMsg('删除成功');

        break;


}