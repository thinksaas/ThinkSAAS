<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

// 所有小组
$page = isset ( $_GET ['page'] ) ? intval ( $_GET ['page'] ) : '1';
$url = tsUrl ( 'group', 'index', array (
		'page' => '' 
) );
$lstart = $page * 24 - 24;
if (intval ( $_GET ['cat'] ) > 0) {
	$sql = "cateid='" . intval ( $_GET ['cat'] ) . "'";
}
$arrGroups = $new ['group']->findAll ( 'group', $sql, 'isrecommend desc,addtime asc', 'groupid', $lstart . ',24' );
$catGroups = $db->fetch_all_assoc ( "select * from " . dbprefix . "group_cate order by cateid ASC" );
foreach ( $catGroups as $key => $val ) {
	$list [] = $val;
}
foreach ( $arrGroups as $key => $item ) {
	$arrData [] = $new ['group']->getOneGroup ( $item ['groupid'] );
}
foreach ( $arrData as $key => $item ) {
	$arrRecommendGroup [] = $item;
	$arrRecommendGroup [$key] ['groupdesc'] = cututf8 ( t ( $item ['groupdesc'] ), 0, 35 );
}

$groupNum = $new ['group']->findCount ( 'group' );

$pageUrl = pagination ( $groupNum, 24, $page, $url );

// 我加入的小组
$myGroup = array ();
if ($TS_USER ['user'] ['userid']) {
	$myGroups = $new ['group']->findAll ( 'group_user', array (
			'userid' => $TS_USER ['user'] ['userid'] 
	), null, 'groupid' );
	foreach ( $myGroups as $item ) {
		$myGroup [] = $item ['groupid'];
	}
}

// 最新10个小组
$arrNewGroup = $new ['group']->getNewGroup ( '10' );

// 热门帖子
$arrTopics = $new ['group']->findAll ( 'group_topic', null, 'count_comment desc', 'groupid,topicid,title,count_comment', 10 );
foreach ( $arrTopics as $key => $item ) {
	$arrTopic [] = $item;
	$arrTopic [$key] ['group'] = $new ['group']->getOneGroup ( $item ['groupid'] );
	$arrTopic [$key] ['title'] = htmlspecialchars ( $item ['title'] );
}

$title = '小组';

if ($TS_CF ['mobile'])
	$sitemb = tsUrl ( 'moblie', 'group' );

include template ( "index" );