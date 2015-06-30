<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );


$page = isset ( $_GET ['page'] ) ? intval ( $_GET ['page'] ) : 1;
$url = tsUrl ( 'group', 'my', array ('my'=>'topic','page' => '' ) );
$lstart = $page * 20 - 20;

$arrTopic = $new['group']->findAll('group_topic',array(
    'userid'=>$strUser['userid'],
),'addtime desc',null,$lstart.',20');

$topicNum = $new ['group']->findCount ( 'group_topic', array (
    'userid' => $strUser['userid'],
) );

$pageUrl = pagination ( $topicNum, 20, $page, $url );

$title = '我的帖子';
include template('my/topic');