<?php
defined('IN_TS') or die('Access Denied.');

$page = tsIntval($_GET['page'],1);

$url = tsUrl('weibo','index',array('page'=>''));

$lstart = $page*20-20;

$arrWeibo = $new['weibo']->findAll('weibo',array(
    'isaudit'=>0,
),'addtime desc',null,$lstart.',20');
foreach($arrWeibo as $key=>$item){
    $arrWeibo[$key]['user'] = aac('user')->getOneUser($item['userid']);
    $arrWeibo[$key]['title'] = tsTitle($item['title']);
    $arrWeibo[$key]['photo'] = $new['weibo']->getWeiboPhoto($item['weiboid'],4);
}

$weiboNum = $new['weibo']->findCount('weibo',array(
    'isaudit'=>0,
));

$pageUrl = pagination($weiboNum, 20, $page, $url);

#热门唠叨
$arrHotWeibo = $new['weibo']->findAll('weibo',null,'count_comment desc',null,10);

foreach($arrHotWeibo as $key=>$item){
    $arrHotWeibo[$key]['title'] = tsTitle($item['title']);
    $arrHotWeibo[$key]['user'] = aac('user')->getOneUser($item['userid']);
    $arrHotWeibo[$key]['photo'] = $new['weibo']->getWeiboPhoto($item['weiboid'],4);
}


$title = '唠叨';
include template('index');