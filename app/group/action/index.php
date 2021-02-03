<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$cateid = tsIntval($_GET['cateid']);

//小组分类
$arrGroupCate = $new['group']->findAll('group_cate',array(
	'referid'=>0,
));


// 所有小组
$page = tsIntval($_GET['page'],1);
$lstart = $page * 32 - 32;
$url = tsUrl ( 'group', 'index', array ('page' => '') );
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

$arrGroup = $new ['group']->findAll ( 'group', $arr, 'isrecommend desc,addtime asc', null, $lstart . ',32' );

foreach ( $arrGroup as $key => $item ) {
	$arrGroup[$key]['groupname'] = tsTitle ( $item['groupname'] );
	$arrGroup[$key]['groupdesc'] = cututf8 (tsTitle($item ['groupdesc']), 0, 35 );
	$arrGroup[$key]['photo_url'] = $new['group']->getGroupPhoto($item);
}

$groupNum = $new ['group']->findCount ( 'group',$arr);

$pageUrl = pagination ( $groupNum, 32, $page, $url );

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

$title = '小组';
if($strCate){
    $title = $strCate['catename'];
}

$sitekey = $TS_APP['appkey'];
$sitedesc = $TS_APP['appdesc'];
include template ( "index" );