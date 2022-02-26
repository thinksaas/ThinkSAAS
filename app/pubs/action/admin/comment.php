<?php
defined('IN_TS') or die('Access Denied.');

switch ($ts){

    case "list":

        $userid = tsIntval($_GET['userid']);
        $ptable = isset($_GET['ptable']) ? trim($_GET['ptable']) : '';
        $pid = tsIntval($_GET['pid']);

        $page = tsIntval($_GET['page'],1);
        $url = SITE_URL.'index.php?app=pubs&ac=admin&mg=comment&ts=list&ptable='.$ptable.'&userid='.$userid.'&page=';
        $lstart = $page*10-10;

        $where = null;

        if($ptable){
            $where = "`ptable`='$ptable'";
        }

        if($userid){
            $where .= " and `userid`='$userid'";
        }

        if($pid){
            $where .= "`pid`='$pid'";
        }

        $arrComment = $new['pubs']->findAll('comment',$where,'addtime desc',null,$lstart.',10');

        $commentNum = $new['pubs']->findCount('comment',$where);

        $pageUrl = pagination($commentNum, 10, $page, $url);

        include template("admin/comment_list");

        break;


    case "delete":

        $commentid = tsIntval($_GET['commentid']);

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
        aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'],$TS_URL['mg'],$TS_URL['api'], $TS_URL['ts'],$strComment['userid']);

        qiMsg('删除成功');

        break;

    case "isaudit":

        $commentid = tsIntval($_GET['commentid']);

        $strComment = $new['pubs']->find('comment',array(
            'commentid'=>$commentid,
        ));

        if($strComment['isaudit']==1){
            $isaudit = 0;
        }else{
            $isaudit = 1;
        }

        $new['pubs']->update('comment',array(
            'commentid'=>$commentid,
        ),array(
            'isaudit'=>$isaudit,
        ));

        qiMsg('操作成功！');

        break;


}