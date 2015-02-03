<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

// 所有小组
$page = isset ( $_GET ['page'] ) ? intval ( $_GET ['page'] ) : '1';
$url = tsUrl ( 'group', 'index', array ('page' => '') );
$lstart = $page * 24 - 24;

$arrGroup = $new ['group']->findAll ( 'group', array('isaudit'=>0), 'isrecommend desc,addtime asc', null, $lstart . ',24' );

foreach ( $arrGroup as $key => $item ) {
	$arrGroup [$key] ['groupname'] = tsTitle ( $item['groupname'] );
	$arrGroup [$key] ['groupdesc'] = cututf8 ( t(tsDecode($item ['groupdesc'])), 0, 35 );
}

$groupNum = $new ['group']->findCount ( 'group',array(
	'isaudit'=>0,
));

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
	$arrTopic [$key] ['title'] = tsTitle ( $item ['title'] );
}

$title = '小组';

if ($TS_CF ['mobile']) $sitemb = tsUrl ( 'moblie', 'group' );
	
$sitekey = $TS_APP['options']['appkey'];
$sitedesc = $TS_APP['options']['appdesc'];

include template ( "index" );