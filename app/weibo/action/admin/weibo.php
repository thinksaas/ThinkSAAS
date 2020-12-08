<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

    case "list":

        $page = isset($_GET['page']) ? tsIntval($_GET['page']) : 1;
        $url = SITE_URL.'index.php?app=weibo&ac=admin&mg=weibo&ts=list&page=';
        $lstart = $page*20-20;
        $arrWeibo = $new['weibo']->findAll('weibo',null,'addtime desc',null,$lstart.',20');
        foreach($arrWeibo as $key=>$item){
            $arrWeibo[$key]['title'] = tsTitle($item['title']);
            $arrWeibo[$key]['photo'] = $new['weibo']->findAll('weibo_photo',array(
                'weiboid'=>$item['weiboid'],
            ));
        }

        $weiboNum = $new['weibo']->findCount('weibo');
        $pageUrl = pagination($weiboNum, 20, $page, $url);

        include template("admin/weibo_list");

    break;


    case "isaudit":

        $weiboid = tsIntval($_GET['weiboid']);

        $strWeibo = $new['weibo']->find('weibo',array(
            'weiboid'=>$weiboid,
        ));

        if($strWeibo['isaudit'] == 0){

            $new['weibo']->update('weibo',array(
                'weiboid'=>$weiboid,
            ),array(
                'isaudit'=>1,
            ));

        }

        if($strWeibo['isaudit'] == 1){

            $new['weibo']->update('weibo',array(
                'weiboid'=>$weiboid,
            ),array(
                'isaudit'=>0,
            ));

        }

        qiMsg('操作成功！');

    break;


    case "delete":

        $weiboid=tsIntval($_GET['weiboid']);

        $new['weibo']->deleteWeibo($weiboid);

        qiMsg('删除成功！');

    break;

}