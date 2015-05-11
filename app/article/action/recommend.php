<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$userid = intval ( $TS_USER ['userid'] );

if ($userid == 0) {
	echo 0;
	exit ();
}

$articleid = intval ( $_POST ['articleid'] );

$isRecommend = $new ['article']->findCount ( 'article_recommend', array (
		'articleid' => $articleid,
		'userid' => $userid 
) );

if ($isRecommend > 0) {
	echo 1;
	exit ();
}

$new ['article']->create ( 'article_recommend', array (
		'articleid' => $articleid,
		'userid' => $userid 
) );

$count_recommend = $new ['article']->findCount ( 'article_recommend', array (
		'articleid' => $articleid 
) );

$new ['article']->update ( 'article', array (
		'articleid' => $articleid 
), array (
		'count_recommend' => $count_recommend 
) );

echo 2;
exit ();