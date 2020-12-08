<?php
defined('IN_TS') or die('Access Denied.');

$page = isset($_GET['page']) ? tsIntval($_GET['page']) : '1';

$url = tsUrl('weibo','my',array('my'=>'index','page'=>''));

$lstart = $page*20-20;

$arrWeibo = $new['weibo']->findAll('weibo',array(
    'userid'=>$strUser['userid'],
),'uptime desc',null,$lstart.',20');

foreach($arrWeibo as $key=>$item){
    $arrWeibo[$key]['title'] = tsTitle($item['title']);
}

$weiboNum = $new['weibo']->findCount('weibo',array(
    'userid'=>$strUser['userid'],
));

$pageUrl = pagination($weiboNum, 20, $page, $url);

$title = '我的唠叨';
include template('my/index');