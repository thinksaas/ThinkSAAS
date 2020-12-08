<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );


$page = isset ( $_GET ['page'] ) ? tsIntval ( $_GET ['page'] ) : 1;
$url = tsUrl ( 'topic', 'my', array ('my'=>'index','page' => '' ) );
$lstart = $page * 20 - 20;

$arrTopic = $new['topic']->findAll('topic',array(
    'userid'=>$strUser['userid'],
),'addtime desc',null,$lstart.',20');

$topicNum = $new ['topic']->findCount ( 'topic', array (
    'userid' => $strUser['userid'],
) );

$pageUrl = pagination ( $topicNum, 20, $page, $url );

$title = '我的帖子';
include template('my/index');