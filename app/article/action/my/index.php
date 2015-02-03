<?php 
defined ( 'IN_TS' ) or die ( 'Access Denied.' );


$page = isset ( $_GET ['page'] ) ? intval ( $_GET ['page'] ) : 1;
$url = tsUrl ( 'article', 'my', array ('my'=>'index','page' => '' ) );
$lstart = $page * 10 - 10;

$arrArticle = $new ['article']->findAll ( 'article', array (
		'userid' => $strUser['userid'],
), 'addtime desc', null, $lstart . ',10' );

$articleNum = $new ['article']->findCount ( 'article', array (
		'userid' => $strUser['userid'],
) );

$pageUrl = pagination ( $articleNum, 10, $page, $url );



$title = '我的文章';
include template('my/index');