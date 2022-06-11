<?php 
defined ( 'IN_TS' ) or die ( 'Access Denied.' );


$page = tsIntval($_GET['page'],1);
$url = tsUrl ( 'comment', 'my', array ('my'=>'index','page' => '' ) );
$lstart = $page * 20 - 20;

$arrComment = $new ['comment']->findAll ( 'comment', array (
		'userid' => $strUser['userid'],
), 'addtime desc', null, $lstart . ',20' );

$commentNum = $new ['comment']->findCount ( 'comment', array (
		'userid' => $strUser['userid'],
) );

$pageUrl = pagination ( $commentNum, 20, $page, $url );



$title = '我的评论';
include template('my/index');