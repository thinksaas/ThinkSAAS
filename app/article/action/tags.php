<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );
$page = isset ( $_GET ['page'] ) ? $_GET ['page'] : '1';

$url = tsUrl ( 'article', 'tags', array (
		'page' => '' 
) );

$lstart = $page * 200 - 200;

$arrTag = $new ['article']->findAll ( 'tag', "`count_article`>'0'", null, null, $lstart . ',200' );

$tagNum = $new ['article']->findCount ( 'tag', "`count_topic`>'0'" );

$pageUrl = pagination ( $tagNum, 200, $page, $url );

$title = '标签';
include template ( 'tags' );