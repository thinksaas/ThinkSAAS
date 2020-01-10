<?php
defined('IN_TS') or die('Access Denied.');

switch ($ts){

    case "list":

        $userid = intval($_GET['userid']);
        $photoid = intval($_GET['photoid']);

        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $url = SITE_URL.'index.php?app=photo&ac=admin&mg=comment&ts=list&page=';
        $lstart = $page*10-10;

        $where = null;

        if($userid){
            $where = array(
                'userid'=>$userid,
            );
        }

        if($photoid){
            $where = array(
                'photoid'=>$photoid,
            );
        }

        $arrComment = $new['photo']->findAll('photo_comment',$where,'addtime desc',null,$lstart.',10');

        $commentNum = $new['photo']->findCount('photo_comment',$where);

        $pageUrl = pagination($commentNum, 10, $page, $url);

        include template("admin/comment_list");

        break;


    case "delete":

        $commentid = intval($_GET['commentid']);

        $strComment = $new['photo']->find('photo_comment',array(
            'commentid'=>$commentid,
        ));

        $new['photo']->delete('photo_comment',array(
            'commentid'=>$commentid,
        ));

        #统计评论数
        $count_comment = $new['photo']->findCount('photo_comment',array(
            'photoid'=>$strComment['photoid'],
        ));

        //更新评论数
        $new['photo']->update('photo',array(
            'photoid'=>$strComment['photoid'],
        ),array(
            'count_comment'=>$count_comment,
        ));


        #处理积分
        aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'],$strComment['userid'],$TS_URL['mg']);

        qiMsg('删除成功');

        break;


}