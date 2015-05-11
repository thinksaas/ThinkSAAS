<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$cateid = intval($_GET['cateid']);

//小组分类
$arrGroupCate = $new['group']->findAll('group_cate',array(
	'referid'=>0,
));


// 所有小组
$page = isset ( $_GET ['page'] ) ? intval ( $_GET ['page'] ) : '1';

$lstart = $page * 24 - 24;

$arr = array(
	'isaudit'=>0
);

if($cateid){

    $strCate = $new['group']->find('group_cate',array(
        'cateid'=>$cateid,
    ));
	
	$url = tsUrl ( 'group', 'index', array ('cateid'=>$cateid,'page' => '') );
	$arr = array(
		'cateid'=>$cateid,
		'isaudit'=>0,
	);
	
}

$arrGroup = $new ['group']->findAll ( 'group', $arr, 'isrecommend desc,addtime asc', null, $lstart . ',24' );

foreach ( $arrGroup as $key => $item ) {
	$arrGroup [$key] ['groupname'] = tsTitle ( $item['groupname'] );
	$arrGroup [$key] ['groupdesc'] = cututf8 ( t(tsDecode($item ['groupdesc'])), 0, 35 );
}

$groupNum = $new ['group']->findCount ( 'group',$arr);

$pageUrl = pagination ( $groupNum, 24, $page, $url );

// 我加入的小组
$myGroup = array ();
if ($TS_USER ['userid']) {
	$myGroups = $new ['group']->findAll ( 'group_user', array (
			'userid' => $TS_USER ['userid'] 
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
if($strCate){
    $title = $strCate['catename'];
}

$sitekey = $TS_APP['appkey'];
$sitedesc = $TS_APP['appdesc'];
include template ( "index" );